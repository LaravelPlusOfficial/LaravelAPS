<?php

namespace App\Models\Traits;


use App\Models\Post;

trait UrlShortner
{

    public static function bootUrlShortner()
    {
        static::created(function ($post) {

            $post->short_url = $post->getShortUrl($post);

            $post->save();

        });
    }

    public function getShortUrl($post)
    {
        $unique = false;

        // While will be repeated until we get unique hash
        while ($unique == false) {

            // Getting full hash based on random numbers
            $full_hash = base64_encode(rand(999, 9999999));

            // Taking only first 8 symbols
            $hash = substr($full_hash, 0, 8);

            // Checking for duplicate in Database - Laravel SQL syntax
            $duplicate = Post::where('short_url', $hash)->exists();

            // If no Duplicate, setting Hash as unique
            if (!$duplicate) {
                $unique = true;
            }
        }

        return $hash;

    }

}