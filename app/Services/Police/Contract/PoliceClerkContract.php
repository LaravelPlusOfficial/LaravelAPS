<?php

namespace App\Services\Police\Contract;


use App\Models\User;

interface PoliceClerkContract
{

    public function getUserAcl(User $user);

}