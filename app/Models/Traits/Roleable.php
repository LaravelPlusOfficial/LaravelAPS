<?php

namespace App\Models\Traits;

use App\Models\Role;
use App\Services\Police\Contract\PoliceContract;

trait Roleable
{

    /**
     * Get Roles of particular user
     *
     * @return mixed
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Get roles with associated permissions of particular user
     *
     * @return mixed
     */
    public function rolesWithPermissions()
    {
        return $this->belongsToMany(Role::class)
            ->with([
                'permissions' => function ($q) {
                    $q->select('id', 'slug', 'label');
                }
            ])
            ->select('id', 'slug', 'label');
    }


    /**
     * @param Role|integer|string $role
     *
     * @return Roleable
     */
    public function assignRole($role)
    {

        if ($role instanceof Role) {
            return $this->roles()->attach($role->id);
        }

        if (is_numeric($role)) {
            return $this->roles()->attach(Role::whereId($role)->firstOrFail()->id);
        }

        if (is_string($role)) {
            return $this->roles()->attach(Role::whereSlug($role)->firstOrFail()->id);
        }

    }

    /**
     * @param array $roles
     *
     * @return mixed
     */
    public function syncRoles($roles = [])
    {
        $this->roles()->detach();

        return $this->roles()->sync($roles);

    }

    /**
     * @param Role|integer|string $role
     *
     * @return bool
     */
    public function hasRole($role)
    {
        if ($role instanceof Role) {
            return resolve(PoliceContract::class)->checkIfUserHasRole($role->slug, $this);
        }

        if (is_numeric($role)) {
            return resolve(PoliceContract::class)->checkIfUserHasRole(Role::whereId($role)->firstOrFail()->slug, $this);
        }

        if (is_string($role)) {
            return resolve(PoliceContract::class)->checkIfUserHasRole($role, $this);
        }

        return false;

    }


}