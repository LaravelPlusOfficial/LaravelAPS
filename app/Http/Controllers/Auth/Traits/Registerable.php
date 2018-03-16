<?php

namespace App\Http\Controllers\Auth\Traits;


use App\Models\Role;
use App\Models\User;
use App\Notifications\WelcomeEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;

trait Registerable
{

    protected $defaultRole;

    protected $rules = [
        'name'     => 'required|string|max:255',
        'email'    => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|max:255|confirmed',
        'data'     => 'string'
    ];


    protected $emailVerified = false;


    protected $avatarUrl = null;


    /**
     * @param bool $emailVerified
     */
    public function setEmailVerified(bool $emailVerified)
    {
        $this->emailVerified = $emailVerified;
    }

    /**
     * @param mixed $defaultRole
     */
    public function setDefaultRole($defaultRole = null)
    {
        $this->defaultRole = $defaultRole ?: Role::whereSlug(config('aps.acl.default_role'))->firstOrFail();
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return \App\Models\User
     */
    protected function create()
    {
        $user = User::create([
            'name'           => $this->request->name,
            'email'          => $this->request->email,
            'password'       => Hash::make($this->request->password),
            'email_verified' => $this->emailVerified
        ]);

        $user->assignRole($this->defaultRole);

        $user->notify(new WelcomeEmail());

        if (method_exists($this, 'storeAvatar')) {

            $this->{'storeAvatar'}($user);

        }

        event(new Registered($user));

        return $user;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator()
    {
        return Validator::make($this->request->all(), $this->rules);
    }

    /**
     * The user has been registered.
     *
     * @param  mixed $user
     * @return mixed
     */
    protected function registered($user)
    {
        if ($user->email_verified) {

            Auth::guard()->login($user);

            return redirect()->route('admin.dashboard');
        }

        return view('auth.emails.confirm-email', compact('user'));
    }

    protected function registerationOpen()
    {
        return setting('users_registeration_enabled') == 'enable';
    }
}