<?php

namespace App\Policies;

use App\Models\Page;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }

    }

    public function manage(User $user, Page $page)
    {
        return $user->can('manage.pages') ? true : ($user->id == $page->author_id);
    }
}
