<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Role extends Model
{

    use HasSlug;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var string
     */
    protected $table = 'roles';

    /**
     * @var bool
     */
    public $timestamps = FALSE;

    protected $hidden = [
        'pivot'
    ];

    protected $appends = ['editable'];

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
     * The users that belong to the role.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function attachPermission($permission)
    {
        if ($permission instanceof Permission) {
            $this->permissions()->attach($permission->id);
        }

        if (is_numeric($permission)) {
            $this->permissions()->attach(Permission::whereId($permission)->firstOrFail()->id);
        }

        if (is_string($permission)) {
            $this->permissions()->attach(Permission::whereSlug($permission)->firstOrFail()->id);
        }

    }

    public function syncPermissions($permissionsIds = [])
    {
        $this->permissions()->detach();

        return $this->permissions()->sync($permissionsIds);
    }

    public function attachPermissions($permissions = [])
    {
        if (is_array($permissions)) {
            foreach ($permissions as $permission) {
                $this->attachPermission($permission);
            }
        }
    }

    public function getEditableAttribute()
    {
        return !in_array($this->slug, $this->getFreezedRolesSlug());
    }

    public function getFreezedRolesSlug()
    {
        return collect(config('aps.acl.freezed_roles', []))->map(function($value, $key) {
            return str_slug($value, config('aps.acl.slug_separator'));
        })->toArray();
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

    public function hasPermission($permission)
    {
        if (is_string($permission)) {
            return $this->permissions->pluck('slug')->contains($permission);
        }

        if($permission instanceof Permission) {
            return $this->permissions->pluck('slug')->contains($permission->slug);
        }

        return false;
    }

}
