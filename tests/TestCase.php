<?php

namespace Tests;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use App\Services\Police\Contract\PoliceContract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    public function tearDown()
    {
        parent::tearDown();

        unset(app()[PoliceContract::class]);;
    }

    protected function registerAcl()
    {
        resolve(PoliceContract::class)->registerPermissions(true);
    }

    /**
     * Sign In a user for testing
     *
     * @param null $user
     *
     * @return $this
     */
    protected function signIn($user = null)
    {
        $user = $user ? $user : $this->create(User::class);

        $this->actingAs($user);

        return $user;
    }

    /**
     * Sign in a user and verified account
     *
     * @param null $user
     * @param bool $signIn
     * @return mixed|null
     */
    protected function signInConfirmed($user = null, $signIn = true)
    {
        $user = $user ? $user : $this->create(User::class, ['email_verified' => true]);

        if ($signIn) {
            $this->actingAs($user);
        }

        return $user;
    }

    protected function signInAdmin()
    {
        $this->seedRolesAndPermissions();

        $roles = Role::get();

        $permissions = Permission::get()->pluck('id')->toArray();

        $adminUser = $this->signInConfirmed(null, false);

        $roles->each(function ($role) use ($permissions, $adminUser) {

            $role->permissions()->sync($permissions);

            if ($role->slug == 'admin') {
                $adminUser->assignRole($role);
            }
        });

        $adminUser = $adminUser->fresh('roles');

        $this->actingAs($adminUser);

        $this->registerAcl();

        return $adminUser->fresh();
    }

    /**
     * @param array $permissions
     * @param bool  $emailVerified
     * @param null  $role
     * @return mixed
     */
    protected function signInWithPermissions($permissions = [], $emailVerified = true, $role = null)
    {
        $role = $role ? $role : $this->create(Role::class);

        if (is_array($permissions)) {

            foreach ($permissions as $permission) {

                $permission = is_string($permission) ? Permission::firstOrCreate(['label' => $permission]) : $permission;

                $role->attachPermission($permission);
            }

        }

        if (is_string($permissions)) {

            $permission = Permission::firstOrCreate(['label' => $permissions]);

            $role->attachPermission($permission);
        }

        $user = $this->create(User::class, ['email_verified' => $emailVerified]);

        $user->assignRole($role);

        $this->signIn($user);

        $this->registerAcl();

        return $user->fresh();

    }

    /**
     * Sign out logged in user
     *
     * @return $this
     */
    protected function signOut()
    {
        auth()->logout();

        return $this;
    }

    /**
     * Create Factory
     *
     * @param       $class
     * @param array $attributes
     * @param null  $times
     *
     * @return mixed
     */
    protected function create($class, $attributes = [], $times = null)
    {
        return factory($class, $times)->create($attributes);
    }

    /**
     * Make Factory
     *
     * @param       $class
     * @param array $attributes
     * @param null  $times
     *
     * @return mixed
     */
    protected function make($class, $attributes = [], $times = null)
    {
        return factory($class, $times)->make($attributes);
    }

    /**
     * @return mixed
     */
    protected function getAllErrors()
    {
        //$errors = request()->session()->get('errors');
        //$messages = $errors->getBag('default')->getMessages();
        //$emailErrorMessage = array_shift($messages['email']);
        //$this->assertEquals('Already in use', $emailErrorMessage);

        return request()->session()->get('errors');
    }

    protected function seedRolesAndPermissions(): void
    {
        $acl = (new \AccessControlTableSeeder());

        foreach ($acl->getRoles() as $roleLabel) {
            $this->create(Role::class, ['label' => $roleLabel]);
        }

        foreach ($acl->getPermissions() as $permissionLabel) {
            $this->create(Permission::class, ['label' => $permissionLabel]);
        }
    }

}
