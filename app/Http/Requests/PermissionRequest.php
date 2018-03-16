<?php

namespace App\Http\Requests;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PermissionRequest extends FormRequest
{
    use RequestType;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $label = $this->label;

        if ($this->requestToCreate()) {
            return [
                'label' => [
                    'required',
                    Rule::unique('permissions')
                        ->where(function ($query) use ($label) {
                            $query->where('label', strtolower($label));
                        })
                ],
            ];
        }

        if ($this->requestToUpdate()) {
            return [
                'label' => [
                    'required',
                    Rule::unique('permissions')
                        ->where(function ($query) use ($label) {
                            $query->where('label', strtolower($label));
                        })->ignore($this->route()->parameter('permission')->id, 'id')
                ],
            ];
        }

        if ($this->requestToDelete()) {
            return [];
        }
    }

    /**
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function persist()
    {
        if ($this->slugAlreadyUsed(str_slug($this->label, '.'))) {
            return back()->withErrors([
                'slug' => 'Please choose different slug. ' . str_slug($this->label, '.') . ' already occupied'
            ]);
        }

        if ($permission = Permission::create(['label' => $this->label])) {

            Role::where('slug', 'admin')
                ->orWhere('slug', 'super.admin')
                ->get()
                ->each(function ($role) use ($permission) {
                    $role->permissions()->attach($permission->id);
                });

            return redirect()->route('admin.permission.index')->with('success', 'New permission created');
        }

        return back()->with('error', 'Something went wrong while creating permission');
    }

    /**
     * @param $permission
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update($permission)
    {
        if (!$this->isEditable($permission)) {
            return redirect()->route('admin.permission.index')
                ->withErrors([
                    'permission' => 'Cannot update freezed permissions'
                ]);
        }

        if ($permission->update(['label' => $this->label])) {
            return redirect()->route('admin.permission.index')
                ->with('success', 'Permission updated');
        }

        return back()->with('error', 'Something went wrong');

    }

    public function delete($permission)
    {
        if ($permission->delete()) {
            return redirect()->route('admin.permission.index');
        }

        return redirect()->back()->with('error', 'Something went wrong');
    }

    /**
     * @param $slug
     * @return mixed
     */
    protected function slugAlreadyUsed($slug)
    {
        return Permission::whereSlug($slug)->exists();
    }

    /**
     * @param $permission
     * @return bool
     */
    protected function isEditable(Permission $permission): bool
    {
        return !in_array($permission->slug, $permission->getFreezedPermissionsSlug());
    }

}
