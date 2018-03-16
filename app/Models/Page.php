<?php

namespace App\Models;

use App\Scopes\PageScope;
use App\Filters\QueryFilter;
use Spatie\Sluggable\HasSlug;
use App\Models\Traits\Metaable;
use App\Models\Traits\Postable;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use Metaable, HasSlug, Postable;

    /**
     * @var string
     */
    protected $table = 'posts';

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

        static::addGlobalScope(new PageScope());
    }


    /**
     * @param $builder
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
