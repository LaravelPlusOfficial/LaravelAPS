<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Page::class, function (Faker $faker) {

    // User
    $users = \App\Models\User::all();

    if ($users->isEmpty()) {
        $userId = factory(\App\Models\User::class)->create()->id;
    } else {
        $userId = $users->pluck('id')->random();
    }
    
    return [
        'title'             => $faker->sentence(),
        'body'              => $faker->paragraph(3),
        'excerpt'           => $faker->paragraph,
        'publish_at'        => date('Y-m-d H:i:s'), // 2018-02-06 12:38:19 - Y-m-d H:i:s
        'featured_image_id' => null,
        'post_type'         => 'page',
        'author_id'         => function () use ($userId) {
            return $userId;
        }
    ];

});

