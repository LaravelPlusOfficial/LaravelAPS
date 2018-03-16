<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\MustBeSocialLink;
use Illuminate\Foundation\Http\FormRequest;
use App\Services\Police\Contract\PoliceContract;

class UserRequest extends FormRequest
{

    /**
     * @var ACL
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
        return [
            'name'             => 'required',
            'profile_image_id' => 'exists:media,id',
            'introduction'     => 'spamfree',
            'country_id'       => 'exists:countries,id',
            'roles'            => 'array',
            'roles.*'          => 'exists:roles,id',
            'social_links'     => new MustBeSocialLink(),
        ];
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(User $user)
    {
        $data = $this->only([
            'name',
            'country_id',
            'profile_image_id',
            'introduction'
        ]);

        $data = $data + ['social_links' => $this->getSocialLinks()];

        $user->update($data);

        if (\Auth::user()->can('manage.acl')) {
            $user->syncRoles($this->roles);
        }

        return redirect()->route('admin.profile.edit', $user->id);

    }

    protected function getSocialLinks()
    {
        $socials = [];

        foreach (config('aps.users.allowed_social_links') as $name => $value) {

            if ($this->social_links[$name]) {

                if (starts_with($value, '@')) {

                    $socials[$name] = trim($this->social_links[$name]);

                } else {

                    $socials[$name] = removeQueryFromUrl($this->social_links[$name]);

                }

            }

        }

        return $socials;
    }

}
