<?php

namespace App\Policies;

use App\Models\User;
use App\Models\User as LoggedInUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * @param $loggedInUser
     * @param $ability
     * @return bool
     */
    public function before($loggedInUser, $ability)
    {
        if ($loggedInUser->isAdmin() ) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User $loggedInUser
     * @param User $user
     * @return mixed
     */
    public function update(LoggedInUser $loggedInUser, User $user = null)
    {
        if( $loggedInUser->can('manage.users') ) {
            return true;
        }

        if ($user) {
            return $loggedInUser->id === $user->id;
        }
    }

}
