<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PermissionRequest;
use App\Models\Permission;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Services\Police\Contract\PoliceContract;

class PermissionsController extends Controller
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
        $this->authorize('manage', Permission::class);

        $permissions = collect($acl->getPermissions())->sortBy('label');

        return view('admin.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('manage', Permission::class);

        $roles = Role::orderBy('label')->get()->groupByFirstLetter('label');

        return view('admin.permissions.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PermissionRequest $request
     * @return PermissionRequest|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(PermissionRequest $request)
    {
        $this->authorize('manage', Permission::class);

        return $request->persist();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param PermissionRequest $request
     * @param Permission $permission
     * @return PermissionRequest|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(PermissionRequest $request, Permission $permission)
    {
        $this->authorize('manage', $permission);

        return $request->delete($permission);
    }
}
