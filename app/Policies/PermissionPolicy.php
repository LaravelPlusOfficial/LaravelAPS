<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * @param User $user
     * @param Permission $permission
     * @return bool
     */
    public function manage(User $user, Permission $permission = null)
    {
        if ($this->permissionIsFreezed($permission)) {
            return false;
        }

        return $user->can('manage.acl');
    }

    protected function permissionIsFreezed($permission)
    {
        if (!$permission) return false;

        return array_first(config('aps.acl.freezed_permissions'), function ($label, $key) use ($permission) {
            return str_slug($label, config('aps.acl.slug_separator', '.')) == optional($permission)->slug;
        });
    }

}
