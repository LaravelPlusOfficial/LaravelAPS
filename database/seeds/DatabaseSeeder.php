<?php

use Illuminate\Database\Seeder;

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

        // $this->call(TaxonomiesTableSeeder::class);

        // $this->call(UsersTableSeeder::class);

        // $this->call(PagesTableSeeder::class);

        // $this->call(PostsTableSeeder::class);

        //$this->call(CommentsTableSeeder::class);
    }
}
