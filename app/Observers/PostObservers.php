<?php

namespace App\Observers;


use App\Models\Post;
use App\Jobs\PublishPostToSocialMedia;

class PostObservers
{

    /**
     * Listen to the User created event.
     *
     * @param Post $post
     * @return void
     */
    public function created(Post $post)
    {

    }

}