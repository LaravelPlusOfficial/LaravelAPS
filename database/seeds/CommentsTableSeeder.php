<?php

use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = \App\Models\Post::latest()->take(6)->get();

        foreach ($posts as $post) {
            factory(\App\Models\Comment::class, array_random([1, 2]))->create([
                'post_id' => $post->id
            ]);
        }

        foreach (range(0, 1) as $i) {
            $comments = \App\Models\Comment::where('status', 'approved')->get();

            foreach ($comments as $comment) {
                factory(\App\Models\Comment::class, array_random([1]))->create([
                    'post_id'   => $comment->post_id,
                    'parent_id' => $comment->id
                ]);
            }

        }
    }
}
