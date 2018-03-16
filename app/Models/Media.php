<?php

namespace App\Models;

use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{

    /**
     * @var string
     */
    protected $table = 'media';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $casts = [
        'variations' => 'array',
        'properties' => 'array'
    ];

    /**
     * @var array
     */
    protected $appends = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function posts()
    {
        return $this->morphedByMany( Post::class, 'mediaable' );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function uploadedBy()
    {
        return $this->belongsTo( User::class, 'uploaded_by' );
    }

    /**
     * @return string
     */
    public function getNameAttribute()
    {
        return "{$this->slug}.{$this->extension}";
    }

    /**
     * @param $builder
     * @param QueryFilter $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($builder, QueryFilter $filter)
    {
        return $filter->apply($builder);
    }

}
