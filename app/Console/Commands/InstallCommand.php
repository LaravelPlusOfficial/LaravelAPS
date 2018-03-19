<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aps:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Laravel APS - Hackable CMS';

    /**
     * @var array
     */
    protected $vars = [];

    /**
     * @var
     */
    protected $dbVars;

    /**
     * @var array
     */
    protected $admin = [
        'name'     => 'Gurinder Chauhan',
        'email'    => 'your@email.com',
        'password' => 'password',
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->checkIfAppInProduction()) {

            $this->question("App is in production");

            $this->question("Continue");

            if (!$this->confirm("")) {
                return false;
            }

        }

        $proceed = $this->welcome();

        if (!$proceed) {
            return false;
        }

        $steps = [
            'createEnvFile',
            'requestAppVars',
            'getDatabaseCredentials',
            'akismet',
            'MailVariables',
            'recaptcha',
            'socialiteKeys',
            'writeEnv',
            'migrateDatabase',
            'createAdmin'
        ];

        $bar = $this->output->createProgressBar(count($steps));

        foreach ($steps as $method) {

            if (method_exists($this, $method)) {

                $result = $this->$method();

                if ($result && is_array($result)) {

                    $this->vars = array_merge($this->vars, $result);

                }

                $this->clear();

            }

            $bar->advance();

            $this->info('');
        }

        $bar->finish();

        $this->info('');

        $this->call('cache:clear');

        $this->goodbye();
    }

    /**
     * Display the welcome message.
     */
    protected function welcome()
    {
        $this->info('>> Welcome to the APS installation process! <<');

        $confirmText = 'Continue?';

        if ($this->confirm($confirmText)) {
            return true;
        }

        return false;
    }

    /**
     * Create the initial .env file.
     */
    protected function createEnvFile()
    {
        if (!file_exists('.env')) {
            copy('.env.example', '.env');
            $this->line('.env file successfully created');
        }

        if (strlen(config('app.key')) === 0) {
            $this->call('key:generate');
            $this->line('~ Secret key properly generated.');
        }
    }

    /**
     * @return array
     */
    protected function requestAppVars()
    {
        $this->spacesWarning();
        $vars = [
            'APP_NAME'     => $this->ask('App Name', '"Laravel APS"'),
            'APP_ENV'      => $this->ask('App Environment', 'local'),
            'APP_DEBUG'    => $this->choice('App Debug Mode', ['true', 'false'], 0),
            'APP_URL'      => $this->ask('App Url', 'http://localhost'),
            'APP_TIMEZONE' => $this->ask('Timezone', 'America/Toronto'),
        ];

        return $vars;
    }

    /**
     * @return array
     */
    protected function akismet()
    {
        return [
            'AKISMET_APIKEY'  => $this->ask('Akismet api key'),
            'AKISMET_BLOGURL' => $this->ask('Akismet blog url', "http://localhost"),
        ];
    }

    /**
     * @return array
     */
    protected function MailVariables()
    {
        return [
            'MAIL_DRIVER'     => $this->ask('Mail driver', 'smtp'),
            'MAIL_HOST'       => $this->ask('Mail Host', 'smtp.mailtrap.io'),
            'MAIL_PORT'       => $this->ask('Mail Port', '465'),
            'MAIL_USERNAME'   => $this->ask('Mail Username', 'hello@example.com'),
            'MAIL_PASSWORD'   => $this->ask('Mail Password', ''),
            'MAIL_ENCRYPTION' => $this->ask('Mail Encryption', 'TLS'),
        ];
    }

    /**
     * @return array
     */
    protected function recaptcha()
    {
        return [
            'GOOGLE_RECAPTCHA_SITE_KEY'   => $this->ask('Google recaptcha site key (Test key provided)', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI'),
            'GOOGLE_RECAPTCHA_SECRET_KEY' => $this->ask('Google recaptcha site key (Test secret provided)', '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe'),
        ];
    }

    /**
     * @return array
     */
    protected function socialiteKeys()
    {
        return [
            'GITHUB_CLIENT_ID'     => $this->ask('Github client id'),
            'GITHUB_CLIENT_SECRET' => $this->ask('Github client secret'),

            'TWITTER_CLIENT_ID'     => $this->ask('Twitter client id (API Key)'),
            'TWITTER_CLIENT_SECRET' => $this->ask('Twitter client secret (API Secret)'),
            'TWITTER_ACCESS_TOKEN'  => $this->ask('Twitter access token (Required to auto post)'),
            'TWITTER_ACCESS_SECRET' => $this->ask('Twitter access secret (Required to auto post)'),

            'FACEBOOK_APP_ID'     => $this->ask('Facebook app id'),
            'FACEBOOK_APP_SECRET' => $this->ask('Facebook app secret'),

            'GOOGLE_CLIENT_ID'     => $this->ask('Google OAuth 2.0 client id (123.apps.googleusercontent.com)'),
            'GOOGLE_CLIENT_SECRET' => $this->ask('Google OAuth 2.0 client secret'),
            'GOOGLE_SERVER_KEY'    => $this->ask('Google server key ( API Key )'),
        ];
    }

    /**
     * Update the .env file from an array of $key => $value pairs.
     *
     * @return void
     */
    protected function writeEnv()
    {
        $envFile = $this->laravel->environmentFilePath();

        foreach ($this->vars as $key => $value) {

            $content = preg_replace("/{$key}=(.*)/", "{$key}={$value}", file_get_contents($envFile));

            file_put_contents($envFile, $content);

        }

    }

    /**
     * Request the local database details from the user.
     *
     * @return array
     */
    protected function getDatabaseCredentials()
    {
        $dbVars = [
            'DB_PORT'     => $this->ask('Database port', 3306),
            'DB_DATABASE' => $this->ask('Database name'),
            'DB_USERNAME' => $this->ask('Database user'),
            'DB_PASSWORD' => $this->ask('Database password (leave blank for no password)'),
        ];

        if ($this->checkDatabaseCredentials($dbVars)) {
            $this->dbVars = $dbVars;
            return $dbVars;
        }

        $this->clearCache();

        $this->error("Invalid database credentials");
        $this->comment("Re-type credentials");

        $this->getDatabaseCredentials();

    }

    /**
     * @param null $vars
     */
    protected function migrateDatabase($vars = null)
    {
        if (!$this->confirm('Do you need database setup help?', true)) {
            return;
        }

        $credentials = array_only($vars ?? $this->dbVars, [
            'DB_DATABASE',
            'DB_PORT',
            'DB_USERNAME',
            'DB_PASSWORD',
        ]);

        if ($this->confirm('Do you want to migrate and seed the database?', false)) {

            if ($this->checkDatabaseCredentials($credentials, true)) {

                $this->call('migrate', [
                    '--force' => true,
                    '--seed'  => true
                ]);

                $this->line('~ Database successfully migrated.');
            }

        } else {

            $this->info('');

            $this->error('Migration required to setup application');

            $this->migrateDatabase($credentials);

        }

    }

    /**
     * Create admin
     */
    protected function createAdmin()
    {
        if (!$this->hasUsersTable()) {
            return;
        }

        $adminDetails = $this->getAdminDetail();

        if ($this->isMissingAdminDetail($adminDetails)) {
            $this->info('');
            $this->error('All fields are required to register admin');
            $this->createAdmin();
        }

        if (!$this->checkIfEmailConfirmed($adminDetails)) {
            $this->info('');
            $this->error('Email does not match');
            $this->createAdmin();
        }

        if (!$this->checkIfPasswordConfirmed($adminDetails)) {
            $this->info('');
            $this->error('Password does not match');
            $this->createAdmin();
        }

        $admin = $admin = User::create([
            'name'           => $adminDetails['name'],
            'email'          => $adminDetails['email'],
            'password'       => Hash::make($adminDetails['password']),
            'email_verified' => true
        ]);;

        $this->assignRoles($admin);

        $this->info(">>> Congratulation admin has been created <<<");
    }

    /**
     * @return mixed
     */
    protected function getAdminDetail()
    {
        $this->spacesWarning();
        $data['name'] = $this->ask('Admin name');

        $this->comment('Email is not editable. you can change from database only');
        $data['email'] = $this->ask('Admin email');
        $data['confirm_email'] = $this->ask('Confirm admin email');

        $data['password'] = $this->ask('Password');
        $data['confirm_password'] = $this->ask('Confirm password');

        return $data;
    }

    /**
     * Display the completion message.
     */
    protected function goodbye()
    {
        $this->info('');

        $this->comment('===========================================');
        $this->comment('=============== DONE ENJOY ================');
        $this->comment('===========================================');
    }

    /**
     * @param $adminDetails
     * @return bool
     */
    protected function isMissingAdminDetail($adminDetails)
    {
        return !collect($adminDetails)->filter(function ($value, $key) {
            return empty($value);
        })->isEmpty();
    }

    /**
     * @param $admin
     */
    protected function assignRoles($admin): void
    {
        $roles = Role::get();

        $roles->each(function ($role) use ($admin) {
            $admin->assignRole($role);
        });
    }

    /**
     * @param      $credentials
     * @param null $time
     * @return bool
     */
    protected function checkDatabaseCredentials($credentials, $time = null)
    {

        foreach ($credentials as $key => $value) {

            $configKey = strtolower(str_replace('DB_', '', $key));

            if ($configKey === 'password' && $value == 'null') {

                config(["database.connections.mysql.{$configKey}" => '']);

                continue;

            }

            config(["database.connections.mysql.{$configKey}" => $value]);

        }

        if (!empty(\DB::getConnections())) {
            DB::purge(DB::getDefaultConnection());
        }

        try {

            DB::connection()->getPdo();

            return true;

        } catch (\Exception $e) {

            return false;

        }

    }

    /**
     * @param $adminDetails
     * @return bool
     */
    protected function checkIfPasswordConfirmed($adminDetails)
    {
        $password = isset($adminDetails['password']) ? $adminDetails['password'] : 'noPassword';

        $confirmPassword = isset($adminDetails['confirm_password']) ? $adminDetails['confirm_password'] : 'noPasswordConfirm';

        if ($password === $confirmPassword) {

            return true;
        }

        return false;
    }

    /**
     * @param $adminDetails
     * @return bool
     */
    protected function checkIfEmailConfirmed($adminDetails)
    {
        $password = isset($adminDetails['email']) ? $adminDetails['email'] : 'noEmail';

        $confirmPassword = isset($adminDetails['confirm_email']) ? $adminDetails['confirm_email'] : 'noEmailConfirm';

        if ($password === $confirmPassword) {

            return true;
        }

        return false;
    }

    /**
     * @param string $example
     */
    protected function spacesWarning($example = '123 1234 12345 123456')
    {
        $this->info('');
        $this->comment('------- NOTE --------------------------');
        $this->comment('If you have space in variable,contain that variable into double quote');
        $this->comment('e.g "' . $example . '"');
        $this->comment('---------------------------------------');
    }

    /**
     * Clear terminal
     */
    protected function clear()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            system('cls');
        } else {
            system('clear');
        }
    }

    /**
     * @return bool
     */
    protected function checkIfAppInProduction()
    {
        try {
            if ($this->hasUsersTable()) {
                return App::environment() == 'production';
            }
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    protected function hasUsersTable()
    {
        try {
            if (Schema::hasTable('users')) {
                return true;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Clear cache
     */
    protected function clearCache()
    {
        $this->call('config:cache');
        $this->call('cache:clear');
        $this->call('config:clear');
    }

}
