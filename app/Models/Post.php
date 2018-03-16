<?php

namespace App\Models;

use App\Models\Traits\UrlShortner;
use App\Scopes\PostScope;
use App\Filters\QueryFilter;
use Spatie\Sluggable\HasSlug;
use App\Models\Traits\Metaable;
use App\Models\Traits\Postable;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Post extends Model
{
    use Metaable, HasSlug, Postable, Notifiable, UrlShortner;

    /**
     * @var string
     */
    protected $table = 'posts';

    protected $casts = [
        'publish_at' => 'datetime'
    ];

    /**
     * @var array
     */
    protected $guarded = [];


    /**
     * @var array
     */
    protected $hidden = ['pivot'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['publish_at'];

    //    protected $withCount = ['comments'];

    /**
     * @var array
     */
    protected $appends = ['status', 'path'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new PostScope());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function category()
    {
        return $this->hasOne(Category::class, 'slug', 'category_slug');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    public function scopePublish($query)
    {
        $query->whereNotNull('publish_at');
    }

    public function isPublished()
    {
        return (bool)$this->publish_at;
    }

    public function isDraft()
    {
        return !$this->isPublished();
    }


    /**
     * @param      $query
     * @param null $filter
     */
    public function scopeFetchAll($query, $filter = null)
    {
        $query->filter($filter)->with('category', 'author', 'featuredImage')->withCount('comments');
    }

    /**
     * @param $query
     * @param $slug
     */
    public function scopeSinglePost($query, $slug)
    {

        $query->whereSlug($slug)->with(['featuredImage', 'category', 'tags', 'author', 'metas']);
    }

    /**
     * @param $query
     * @param $postId
     */
    public function scopePrevPost($query, $postId)
    {
        $query->where('id', '<', $postId)->select('id', 'title', 'slug')->orderBy('id', 'desc');
    }

    /**
     * @param $query
     * @param $postId
     */
    public function scopeNextPost($query, $postId)
    {
        $query->where('id', '>', $postId)->select('id', 'title', 'slug')->orderBy('id');
    }

    /**
     * @param             $builder
     * @param QueryFilter $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($builder, QueryFilter $filter = null)
    {
        if ($filter) {
            return $filter->apply($builder);
        }

        return $builder;
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions()
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->doNotGenerateSlugsOnUpdate()
            ->saveSlugsTo('slug');
    }

}
