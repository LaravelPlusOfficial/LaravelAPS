<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Police\Contract\PoliceContract;
use App\Services\Country\Contract\CountryContract;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('update', User::Class);

        $users = User::paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @param PoliceContract $acl
     * @param CountryContract $country
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit($user, PoliceContract $acl, CountryContract $country)
    {
        $user = (Auth::id() === intval($user)) ? Auth::user() : User::whereId($user)->firstOrFail();

        $this->authorize('update', $user);

        $userAcl = $acl->getUserAcl($user);

        return view('admin.profile.edit')->with([
            'profileUser'            => $user,
            'countries'              => $country->getCountries(),
            'roles'                  => $acl->getRoles(),
            'permissions'            => $acl->getPermissions(),
            'profileUserPermissions' => $userAcl['permissions'],
            'profileUserRoles'       => $userAcl['roles']
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        return $request->update($user);
    }

}
