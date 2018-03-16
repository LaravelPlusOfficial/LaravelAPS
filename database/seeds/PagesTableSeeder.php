<?php

use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Page::create([
            'title'     => 'Terms',
            'body'      => $this->terms(),
            'post_type' => 'page',
            'author_id' => 1
        ]);

        \App\Models\Page::create([
            'title'     => 'Policy',
            'body'      => $this->privacy(),
            'post_type' => 'page',
            'author_id' => 1
        ]);

        \App\Models\Page::create([
            'title'     => 'Contact',
            'body'      => null,
            'post_type' => 'page',
            'author_id' => 1
        ]);
    }

    protected function terms() {
        return "<h1> Terms and Conditions </h1>";
    }

    protected function privacy() {
        return "<h1> Privacy Policy </h1>";
    }
}
