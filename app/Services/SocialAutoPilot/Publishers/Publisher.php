<?php

namespace App\Services\SocialAutoPilot\Publishers;


use App\Models\Post;

interface Publisher
{

    /**
     * Publish Post to provider
     *
     * @param Post $post
     * @return mixed
     */
    public function publish(Post $post);

}