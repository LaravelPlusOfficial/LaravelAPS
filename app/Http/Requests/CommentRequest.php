<?php

namespace App\Http\Requests;

use App\Models\Comment;
use App\Rules\Recaptcha;
use Illuminate\Foundation\Http\FormRequest;
use Stevebauman\Purify\Facades\Purify;

class CommentRequest extends FormRequest
{
    use RequestType;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param Recaptcha $recaptcha
     * @return array
     */
    public function rules(Recaptcha $recaptcha)
    {
        if ($this->method() == 'GET') {
            return [
                'post_id' => 'required|exists:posts,id'
            ];
        }

        if ($this->requestToCreateNewComment()) {
            return [
                'body'               => 'required|spamfree',
                'post_id'            => 'required|exists:posts,id',
                'gRecaptchaResponse' => ['required', $recaptcha]
            ];
        }

        if ($this->requestToCreateNewReply()) {
            return [
                'body'               => 'required|spamfree',
                'parent_id'          => 'required|exists:comments,id',
                'gRecaptchaResponse' => ['required', $recaptcha]
            ];
        }

        if ($this->requestToUpdate()) {
            return [
                'body'               => 'required|spamfree',
                'gRecaptchaResponse' => ['required', $recaptcha]
            ];
        }

        if ($this->requestToDelete()) {
            return [
                'gRecaptchaResponse' => ['required', $recaptcha]
            ];
        }
    }

    /**
     * Add New Root level comment to the post
     *
     * @return mixed
     * @throws \Exception
     */
    public function addComment()
    {
        return $this->createComment($this->post_id)->load('replies', 'owner');

    }

    /**
     * Add reply to particular comment
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function addReply()
    {
        $commentParent = Comment::whereId($this->parent_id)->firstOrFail();

        if ($commentParent->isSpam()) {
            return response("Cannot reply spam comment", 500);
        }

        //$this->replyingOwnComment($parent)
        if ( $this->user()->owns($commentParent)) {
            return response("You can not reply your own comment", 403);
        }

        return $this->createComment($commentParent->post_id)->load('replies', 'owner');
    }

    /**
     * @param $postId
     * @return mixed
     * @throws \Exception
     */
    protected function createComment($postId)
    {
        $comment = Comment::create([
            'parent_id' => $this->parent_id,
            'body'      => $this->getSanitizedBody(),
            'post_id'   => $postId,
            'user_id'   => auth()->id()
        ]);

        $this->checkAkismetSpam($comment);

        return $comment;
    }

    public function update(Comment $comment)
    {
        if ($comment->update(['body' => $this->body])) {
            return $comment->fresh(['replies', 'owner']);
        }

        return response("Error while updating", 500);
    }

    /**
     * Get Collection of comments for particular post
     * Post Id should be provide as url parameter in GET request
     *
     * @return mixed
     */
    public function getCommentsForPost()
    {
        $comments = Comment::wherePostId($this->post_id)
            ->approved()
            ->whereParentId(null)
            ->orderBy('created_at', 'desc')
            ->with([
                'replies' => function ($query) {
                    $query->where('status', 'approved');
                    $query->orderBy('created_at', 'desc');
                }
            ])
            ->get();

        return $comments;
    }

    /**
     * @return string
     */
    protected function getSanitizedBody(): string
    {
        return Purify::clean(trim($this->body));
    }

    /**
     * @return bool
     */
    protected function requestToCreateNewReply(): bool
    {
        return (
            ($this->route()->getActionName() == 'App\Http\Controllers\App\CommentsController@addReply') &&
            ($this->method() == 'POST')
        );
    }

    /**
     * @return bool
     */
    public function requestToCreateNewComment(): bool
    {
        return $this->method() == 'POST' && !$this->requestToCreateNewReply();
    }

    protected function replyingOwnComment($parent)
    {
        return (bool)($parent->user_id == auth()->id());
    }

    /**
     * @param Comment $comment
     * @return bool
     * @throws \Exception
     */
    protected function checkAkismetSpam(Comment $comment)
    {
        $akismet = akismet();

        if ($akismet->validateKey()) {

            $akismet->setCommentAuthor(\Auth::user()->name)
                ->setCommentAuthorEmail(\Auth::user()->email)
                ->setCommentContent($comment->body)
                ->setCommentType('comment');


            $comment->status = $akismet->isSpam() ? 'spam' : 'approved';

            return $comment->save();

        }

        $comment->status = 'pending';

        return $comment->save();
    }
}
