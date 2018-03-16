<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function un_authenticated_user_should_not_access_user_profile()
    {
        $user = $this->create(User::class);

        $this->get(route('admin.profile.edit', $user->id))->assertStatus(302);
        $this->patch(route('admin.profile.update', $user->id))->assertStatus(302);
        $this->delete(route('admin.profile.destroy', $user->id))->assertStatus(302);
    }

    /** @test */
    public function authenticated_user_should_able_to_see_only_own_profile()
    {


        $jenny = $this->create(User::class);

        $john = $this->signInConfirmed();

        $this->get(route('admin.profile.edit', $jenny->id))->assertStatus(403);

                $this->withoutExceptionHandling();

        $this->get(route('admin.profile.edit', $john->id))->assertViewIs('admin.profile.edit');
    }

    /** @test */
    public function authenticated_user_should_not_update_somebody_else_profile()
    {
        $this->withoutExceptionHandling();

        $jenny = $this->create(User::class);

        $this->signInConfirmed();

        $this->withoutExceptionHandling()
            ->expectException('\Illuminate\Auth\Access\AuthorizationException');

        $this->patch(route('admin.profile.update', $jenny->id), $jenny->toArray())->assertStatus(403);
    }

    /** @test */
    public function authenticated_user_can_update_own_profile()
    {
        $signedUser = $this->signInConfirmed();

        $this->patch(route('admin.profile.update', $signedUser->id), $signedUser->toArray())
            ->assertRedirect(route('admin.profile.edit', $signedUser->id));
    }


    /** @test */
    public function a_user_with_valid_permission_should_able_to_edit_other_users_profile()
    {
        $john = $this->create(User::class, ['name' => 'john doe']);

        $this->signInConfirmed();

        $this->patch(route('admin.profile.update', $john->id), array_merge($john->toArray(), ['name' => 'adam doe']));

        $this->assertNotEquals('adam doe', $john->fresh()->name);

        $user = $this->signInAdmin();

        $this->patch(route('admin.profile.update', $john->id), array_merge($john->toArray(), ['name' => 'adam doe']))
            ->assertRedirect(route('admin.profile.edit', $john->id));

        $this->assertEquals('adam doe', $john->fresh()->name);
    }

    /** @test */
    public function user_should_not_able_to_update_his_own_role()
    {
        $user = $this->signInConfirmed();

        $anyRole = $this->create(Role::class, ['label' => 'any role']);

        $this->patch(route('admin.profile.update', $user->id), $user->toArray() + ['roles' => [$anyRole->id]])
            ->assertRedirect(route('admin.profile.edit', $user->id));

        $this->assertNotEquals($anyRole->slug, optional($user->fresh('roles')->roles->first())->slug);
    }

    /** @test */
    public function only_admin_can_assign_roles_to_other_users()
    {
        $this->withoutExceptionHandling();

        $user = $this->create(User::class, ['email_verified' => true]);

        $anyRole = $this->create(Role::class, ['label' => 'any role']);

        $this->signInAdmin();

        $this->patch(route('admin.profile.update', $user->id), $user->toArray() + ['roles' => [$anyRole->id]])
            ->assertRedirect(route('admin.profile.edit', $user->id));

        $this->assertEquals($anyRole->slug, optional($user->fresh('roles')->roles->first())->slug);
    }

}
