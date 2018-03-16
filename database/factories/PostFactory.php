<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Post::class, function (Faker $faker) {

    // User
    $users = \App\Models\User::all();

    if ($users->isEmpty()) {
        $userId = factory(\App\Models\User::class)->create()->id;
    } else {
        $userId = $users->pluck('id')->random();
    }

    // Category
    $categories = \App\Models\Category::all();

    if ($categories->isEmpty()) {
        $categorySlug = factory(\App\Models\Category::class)->create()->slug;
    } else {
        $categorySlug = $categories->pluck('slug')->random();
    }


    return [
        'title'             => $faker->sentence(),
        'body'              => $faker->paragraph(3),
        'excerpt'           => $faker->paragraph,
        'publish_at'        => date('Y-m-d H:i:s'), // 2018-02-06 12:38:19 - Y-m-d H:i:s
        'featured_image_id' => null,
        'post_type'         => 'post',
        'post_format'       => str_slug(array_random(config('aps.post.formats')), '_'),
        'category_slug'     => function () use ($categorySlug) {
            return $categorySlug;
        },
        'author_id'         => function () use ($userId) {
            return $userId;
        }
    ];

});
