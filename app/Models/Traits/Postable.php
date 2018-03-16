<?php

namespace App\Models\Traits;


use App\Models\User;
use App\Models\Media;

trait Postable
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function featuredImage()
    {
        return $this->hasOne(Media::class, 'id', 'featured_image_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function media()
    {
        return $this->morphToMany(Media::class, 'mediaable');
    }

    /**
     * @return string
     */
    public function getStatusAttribute()
    {
        return $this->publish_at ? 'published' : 'draft';
    }

    /**
     * @return string
     */
    public function getPathAttribute()
    {
        return $this->slug ? route('post.show', $this->slug) : '';
    }
}