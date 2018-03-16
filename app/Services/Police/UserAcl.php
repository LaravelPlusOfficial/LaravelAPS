<?php

namespace App\Services\Police;


use App\Models\User;

class UserAcl
{

    /**
     * @var User
     */
    protected $user;

    /**
     * @var
     */
    protected $rolesWithPermissions;

    /**
     * @var
     */
    protected $roles;

    /**
     * @var
     */
    protected $permissions;

    /**
     * UserAcl constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;

        $this->fetchAcl()->sortAcl();
    }

    /**
     * Fetch Roles and permissions from user model
     *
     * @return $this
     */
    protected function fetchAcl()
    {
        $this->rolesWithPermissions = collect($this->user->load('rolesWithPermissions')->rolesWithPermissions->toArray());

        return $this;
    }

    /**
     * Sort roles and permissions fetched off of user
     */
    protected function sortAcl()
    {
        $this->rolesWithPermissions->each(function ($item, $key) {

            $this->roles[$item['slug']] = collect($item)->only('id', 'slug', 'label');

            collect($item['permissions'])->each(function ($item, $key) {

                $this->permissions[$item['slug']] = collect($item)->only('id', 'slug', 'label');

            });

        });
    }

    /**
     * Get Requested user Id
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->user->id;
    }

    /**
     * Get Requested user roles
     *
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Get Requested user permissions
     *
     * @return mixed
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

}