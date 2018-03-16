<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Comment::class, function (Faker $faker) {

    $users = \App\Models\User::all();

    if ($users->isEmpty()) {
        $userId = factory(\App\Models\User::class)->create()->id;
    } else {
        $userId = $users->pluck('id')->random();
    }

    $post = \App\Models\Post::all();

    if ($post->isEmpty()) {
        $postId = factory(\App\Models\Post::class)->create()->id;
    } else {
        $postId = $post->pluck('id')->random();
    }


    return [
        'body'      => $faker->sentence,
        'parent_id' => null,
        'user_id'   => function () use ($userId) {
            return $userId;
        },
        'post_id'   => function () use ($postId) {
            return $postId;
        },
        'status'    => array_random(['pending', 'approved'])

    ];
});
