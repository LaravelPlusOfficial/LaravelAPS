<?php

namespace App\Services\Police\Contract;

use App\Models\User;

interface PoliceContract
{

    /**
     * @param bool $force
     * @return mixed
     */
    public function registerPermissions($force = false);


    /**
     * @param $permissionSlug
     * @param User $user
     * @return mixed
     */
    public function checkIfUserHasPermission($permissionSlug, User $user);

    /**
     * @param $roleSlug
     * @param User $user
     * @return mixed
     */
    public function checkIfUserHasRole($roleSlug, User $user);

    /**
     * @return mixed
     */
    public function getPermissions();

    /**
     * @return mixed
     */
    public function getRoles();

    /**
     * @param User $user
     * @return mixed
     */
    public function getUserAcl(User $user);

    /**
     * @param array $roleArray
     * @param string $permissionSlug
     * @return bool
     */
    public function roleHasPermission($roleArray = [], $permissionSlug = '');

}