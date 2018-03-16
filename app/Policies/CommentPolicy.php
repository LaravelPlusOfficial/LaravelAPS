<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }

    }

    /**
     * Policy to update comment
     *
     * @param User $user
     * @param Comment $comment
     * @return bool
     */
    public function manage(User $user, Comment $comment)
    {
        return $user->can('manage.comments');
    }

    /**
     * Policy to update comment
     *
     * @param User $user
     * @param Comment $comment
     * @return bool
     */
    public function update(User $user, Comment $comment)
    {
        return $user->can('manage.comments') ? true : ($user->id == $comment->user_id);
    }
}
