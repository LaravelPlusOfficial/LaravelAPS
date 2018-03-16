<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Avatar\Contract\AvatarContract;

class AvatarController extends Controller
{

    public function update(User $user, Request $request, AvatarContract $avtar)
    {
        $this->authorize('update', \Auth::user());

        $this->validate($request, [
            'avatar' => 'required|mimes:jpeg,jpg,png|max:5000'
        ]);

        return $avtar->store($user, $request->avatar);
    }

    /**
     * @param User $user
     * @param AvatarContract $avatar
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(User $user, AvatarContract $avatar)
    {
        $this->authorize('update', \Auth::user());

        return $avatar->delete($user);
    }

}
