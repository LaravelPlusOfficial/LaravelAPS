<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CountriesTableSeeder::class);

        $this->call(SettingsTableSeeder::class);

        $this->call(AccessControlTableSeeder::class);

        if (App::environment() == 'local') {

            $this->call(TaxonomiesTableSeeder::class);

            $this->call(UsersTableSeeder::class);

            $this->call(PagesTableSeeder::class);

            $this->call(PostsTableSeeder::class);

            $this->call(CommentsTableSeeder::class);

        }

    }
    
}
