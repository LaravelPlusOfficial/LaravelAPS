<?php

namespace App\Services\Inspections\Akismet;


use App\Models\Comment;
use App\Models\User;
use App\Services\Inspections\Contract\SpamContract;

class AkismetSpam implements SpamContract
{

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Comment
     */
    protected $comment;

    public function __construct(User $user, Comment $comment)
    {
        $this->user = $user;

        $this->comment = $comment;
    }


    public function detect($body)
    {
        // TODO: Implement detect() method.
    }
}