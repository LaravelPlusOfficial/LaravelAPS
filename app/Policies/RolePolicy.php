<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;


    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }

    }

    public function manage(User $user, Role $role = null)
    {
        if ($this->roleIsFreezed($role)) {
            return false;
        }

        return $user->can('manage.acl');
    }

    protected function roleIsFreezed($role)
    {
        if(! $role ) return false;

        return array_first(config('aps.acl.freezed_roles', []), function ($label, $key) use ($role) {
            return str_slug($label, config('aps.acl.slug_separator', '.')) == optional($role)->slug;
        });
    }

}
