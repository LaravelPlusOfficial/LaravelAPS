<?php

use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createPosts();
    }


    protected function createPosts()
    {
        factory(\App\Models\Post::class, 50)->create(['post_type' => 'post']);

        $posts = \App\Models\Post::get();

        $tags = \App\Models\Tag::get();

        $posts->each(function ($post) use ($tags) {

            $tags = $tags->pluck('id')->random(2)->toArray();

            $post->tags()->sync($tags);
        });
    }
}
