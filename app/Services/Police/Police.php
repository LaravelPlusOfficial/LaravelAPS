<?php

namespace App\Services\Police;


use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Services\Police\Contract\PoliceClerkContract;
use App\Services\Police\Contract\PoliceContract;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class Police implements PoliceContract
{
    /**
     * @var array
     */
    public $usersAcl = [];

    /**
     * @var
     */
    public $permissions;

    /**
     * @var
     */
    public $roles;

    protected $clerk;

    /**
     * Register Permissions for Authenticated User
     * @param bool $force
     */
    public function registerPermissions($force = false)
    {
        if ($force || ! App::runningInConsole()) {

            $this->permissions = $this->getPermissions();

            $this->roles = $this->getRoles();

            foreach ($this->permissions as $permission) {

                \Gate::define($permission['slug'], function (Authenticatable $user) use ($permission) {

                    return $this->checkIfUserHasPermission($permission['slug'], $user);

                });

            }

        }

    }

    /**
     * Check is User has a permission
     *
     * @param $permission
     * @param User $user
     * @return bool
     */
    public function checkIfUserHasPermission($permission, User $user)
    {
        $authPermissions = $this->getUserAcl($user)['permissions'];

        return isset($authPermissions[$permission]) ? !($authPermissions[$permission])->isEmpty() : false;
    }

    /**
     * Check if user has a role
     *
     * @param $roleSlug
     * @param User $user
     * @return bool
     */
    public function checkIfUserHasRole($roleSlug, User $user)
    {
        $authRoles = $this->getUserAcl($user)['roles'];

        return isset($authRoles[$roleSlug]) ? !($authRoles[$roleSlug])->isEmpty() : false;
    }

    /**
     * Get User Roles and permissions from UserAcl Class
     *
     * @param User $user
     * @return mixed
     */
    public function getUserAcl(User $user)
    {
        if (!$this->clerk) {

            $this->clerk = resolve(PoliceClerkContract::class);

        }

        $this->usersAcl[$user->id] = $this->clerk->getUserAcl($user);

        return $this->usersAcl[$user->id];
    }

    /**
     * Get All the Permissions
     *
     * @return array
     */
    public function getPermissions()
    {
        if ($this->permissions) {
            return $this->permissions;
        }

        $this->permissions = Cache::remember(md5('permissions'), 60 * 24, function () {
            return Permission::with('roles')->get()->toArray();
        });

        return $this->permissions;

    }

    /**
     * Get all the roles
     *
     * @return array
     */
    public function getRoles()
    {
        if ($this->roles) {
            return $this->roles;
        }

        $this->roles = Cache::remember(md5('roles'), 60 * 24, function () {
            return Role::with('permissions')->get()->toArray();
        });

        return $this->roles;
    }

    /**
     * @param array $roleArray
     * @param string $permissionSlug
     * @return bool
     */
    public function roleHasPermission($roleArray = [], $permissionSlug = '')
    {
        return collect($roleArray['permissions'])->pluck('slug')->contains($permissionSlug);
    }

    protected function hasPermissionTable()
    {
        return Cache::rememberForever(md5('has.permission.table'), function () {
            return Schema::hasTable('permissions');
        });
    }


}