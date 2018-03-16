<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Services\Police\Contract\PoliceContract;

class RoleRequest extends FormRequest
{
    use RequestType;

    /**
     * @var PoliceContract
     */
    protected $acl;

    public function __construct(PoliceContract $acl)
    {
        parent::__construct();

        $this->acl = $acl;
    }

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
                'label'         => [
                    'required',
                    Rule::unique('roles')
                        ->where(function ($query) use ($label) {
                            $query->where('label', strtolower($label));
                        })
                ],
                'permissions'   => 'array',
                'permissions.*' => 'exists:permissions,id',
            ];
        }

        if ($this->requestToUpdate()) {
            return [
                'label'         => [
                    'required',
                    Rule::unique('roles')
                        ->where(function ($query) use ($label) {
                            $query->where('label', strtolower($label));
                        })->ignore($this->route()->parameter('role')->id, 'id')
                ],
                'permissions'   => 'array',
                'permissions.*' => 'exists:permissions,id',
            ];
        }

        if ($this->requestToDelete()) {
            return [];
        }

    }

    /**
     * @return Role|\Illuminate\Http\RedirectResponse
     */
    public function persist()
    {
        if ($this->slugAlreadyUsed($this->getSlug($this->label))) {

            return back()->withErrors([
                'slug' => 'Please choose different role label. "' . $this->label . '" already occupied'

            ]);
        }

        if ($role = Role::create(['label' => $this->label])) {

            $role->permissions()->sync($this->permissions);

            return redirect()->route('admin.role.index', ['role-show' => $role->id])->with('success', 'New role created');

        }

        return back()->with('error', 'Something went wrong while creating role');


    }


    /**
     * @param $role
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update($role)
    {
        if ($role->update(['label' => $this->label])) {

            $this->role->permissions()->sync($this->permissions);

            return redirect()->route('admin.role.index', ['role-show' => $role->id])
                ->with('success', 'Role updated');
        }

        return response("Error", 500);
    }

    public function delete($role)
    {
        if ($role->delete()) {

            return redirect()->route('admin.role.index');
        }

        return redirect()->back()->with('error', 'Something went wrong');
    }

    /**
     * @param $slug
     * @return mixed
     */
    protected function slugAlreadyUsed($slug)
    {
        return Role::where('slug', $slug)->exists();
    }


    protected function getSlug($label = null)
    {
        return str_slug(
            $label ?? $this->label,
            config('aps.acl.slug_separator', '.')
        );
    }

}
