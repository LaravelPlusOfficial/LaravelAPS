<?php

namespace App\Http\Controllers\App;

use App\Models\Comment;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;

class CommentsController extends Controller
{

    /**
     * Get comment collection for post
     *
     * @param CommentRequest $request
     * @return mixed
     */
    public function index(CommentRequest $request)
    {
        return $request->getCommentsForPost();
    }

    /**
     * Store a new comment to database
     *
     * @param CommentRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function store(CommentRequest $request)
    {
        return $request->addComment();
    }

    /**
     * Add Reply to particular comment
     *
     * @param CommentRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function addReply(CommentRequest $request)
    {
        return $request->addReply();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param CommentRequest $request
     * @param Comment        $comment
     * @return Comment
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(CommentRequest $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        return $request->update($comment);
    }

    /**
     * Remove the specified resource from storage.
     * @param CommentRequest $request
     * @param Comment        $comment
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(CommentRequest $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        if ($comment->delete()) {
            return response("Comment deleted", 200);
        }

        return response("Error while deleting comment", 500);
    }

}
