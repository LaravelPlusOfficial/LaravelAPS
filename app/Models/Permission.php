<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Permission extends Model
{
    use HasSlug;

    protected $guarded = [];

    /**
     * @var string
     */
    protected $table = 'permissions';

    protected $hidden = [
        'pivot'
    ];

    protected $appends = ['editable'];

    /**
     * @var bool
     */
    public $timestamps = FALSE;

    /**
     * Set label to lower case
     *
     * @param  string  $value
     * @return void
     */
    public function setLabelAttribute($value)
    {
        $this->attributes['label'] = strtolower($value);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * @param int|array $role
     *
     * @return array
     */
    public function attachToRoles($role)
    {
        return $this->roles()->sync($role);
    }

    public function scopeGroupedByModal($query)
    {
        return $query->select('id', 'slug', 'label')->orderBy('slug');
    }


    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('label')
            ->doNotGenerateSlugsOnUpdate()
            ->saveSlugsTo('slug')
            ->usingSeparator('.');
    }

    public function getEditableAttribute()
    {
        return ! in_array($this->slug, $this->getFreezedPermissionsSlug());
    }

    public function getFreezedPermissionsSlug()
    {
        return [
            'manage.acl'
        ];
    }
}
