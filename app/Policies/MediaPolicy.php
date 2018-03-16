<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Media;
use Illuminate\Auth\Access\HandlesAuthorization;

class MediaPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }

    }

    public function manage(User $user, Media $media)
    {

        return $user->can('manage.media') ? true : ($user->id == $media->uploaded_by);

//        if ($user->can('manage.media')) {
//            return true;
//        }
//
//        if ($user->id == $media->uploaded_by) {
//            return true;
//        }
    }
}
