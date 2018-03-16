<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Post;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }

    }

    public function manage(User $user, Post $post)
    {
        return $user->can('manage.posts') ? true : ($user->id == $post->author_id);
    }
}
