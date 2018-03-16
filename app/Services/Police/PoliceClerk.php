<?php

namespace App\Services\Police;


use App\Models\User;
use App\Services\Police\Contract\PoliceClerkContract;

class PoliceClerk implements PoliceClerkContract
{

    protected $usersAcl = [];

    public function getUserAcl(User $user)
    {
        if (isset($this->usersAcl[$user->id])) {

            return $this->usersAcl[$user->id];

        }

        $this->usersAcl[$user->id] = $this->fetchAcl($user);

        return $this->usersAcl[$user->id];

    }

    protected function fetchAcl($user)
    {
        $acl = new UserAcl($user);

        return [
            'id'          => $acl->getId(),
            'roles'       => $acl->getRoles() ?: [],
            'permissions' => $acl->getPermissions() ?: []
        ];
    }

}