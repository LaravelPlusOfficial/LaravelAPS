<?php
namespace App\Observers;


use App\Models\Comment;

class CommentObservers
{

    /**
     * Listen to the User deleting event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function deleted(Comment $comment)
    {
        $replies = Comment::whereParentId($comment->id)->get(['id'])->pluck('id')->toArray();

        Comment::destroy($replies);
    }

}