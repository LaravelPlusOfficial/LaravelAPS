<?php

namespace App\Models;

use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    protected $guarded = [];

    protected $with = ['owner'];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id')->select('id', 'title', 'slug');
    }

    /**
     * Commentator
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id', 'name', 'avatar');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->orderBy('created_at', 'desc')->with('replies');
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function path()
    {
        return route('post.show', $this->post_id) . '#comment-' . $this->id;
    }

    public function scopeVisibleTo($query, User $user)
    {
        if ($user->can('manage.comments')) {
            return $query;
        }

        return $query->where('user_id', $user->id);
    }

    public function location()
    {
        return route('post.show', [$this->post->slug, 'go-to' => 'comment-' . $this->id]);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function isSpam()
    {
        return $this->status == 'spam';
    }

}
