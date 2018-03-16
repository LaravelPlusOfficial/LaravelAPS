<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Http\Requests\RoleRequest;
use App\Http\Controllers\Controller;
use App\Services\Police\Contract\PoliceContract;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param PoliceContract $acl
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(PoliceContract $acl)
    {
        $this->authorize('manage', Role::class);

        $roles = $acl->getRoles();

        $permissions = collect($acl->getPermissions())->groupByFirstLetter('label');

        return view('admin.roles.index', compact('roles', 'permissions', 'acl'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RoleRequest $request
     *
     * @return \App\Models\Role|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(RoleRequest $request)
    {
        $this->authorize('manage', Role::class);

        return $request->persist();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param RoleRequest $request
     * @param Role $role
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(RoleRequest $request, Role $role)
    {
        $this->authorize('manage', $role);

        return $request->update($role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param RoleRequest $request
     * @param Role $role
     * @return RoleRequest|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(RoleRequest $request, Role $role)
    {
        $this->authorize('manage', $role);

        return $request->delete($role);
    }
}
