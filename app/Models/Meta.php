<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    /**
     * @var string
     */
    protected $table = 'metas';

    protected $guarded = [];

    public $timestamps = FALSE;

    protected $casts = [
        'value' => 'array'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($meta) {

            if ($meta->key == 'auto_post_facebook' || $meta->key == 'auto_post_twitter') {
                $meta->value = $meta->value ? 'enable' : 'disable';
            }

            return $meta;
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function metaable()
    {
        return $this->morphTo();
    }


}
