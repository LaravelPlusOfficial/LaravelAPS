<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        //'value'   => 'json',
        'options'      => 'json',
        'info'         => 'json',
        'page_visible' => 'boolean',
        'disabled'     => 'boolean'
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($setting) {
            $setting->key = str_slug("{$setting->group} {$setting->label}", "_");
        });
    }

    public function scopeGroups($query)
    {
        return $query->select('group')->orderBy('group')->groupBy('group')->get()->pluck('group')->toArray();
    }
}
