<?php

namespace Tests\Feature;

use App\Models\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageRolesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_users_cannot_perform_crud_operations_on_roles()
    {
        $this->get(route('admin.role.index'))->assertRedirect('/login');
        $this->get(route('admin.role.create'))->assertRedirect('/login');
        $this->get(route('admin.role.edit', 'role-slug'))->assertRedirect('/login');
        $this->post(route('admin.role.store'), [])->assertRedirect('/login');
        $this->patch(route('admin.role.update', 'role-slug'), [])->assertRedirect('/login');
        $this->delete(route('admin.role.destroy', 'role-slug'))->assertRedirect('/login');
    }

    /** @test */
    public function role_can_be_created()
    {
        $this->withoutExceptionHandling();

        $this->signInWithPermissions('manage acl');

        $role = $this->make(Role::class, ['label' => 'role label'])->toArray();

        $expectedSlug = str_slug($role['label'], '.');

        $this->post(route('admin.role.store'), $role);

        $roleFromDatabase = Role::whereSlug($expectedSlug)->firstOrFail();

        $this->assertEquals($expectedSlug, $roleFromDatabase->slug);
    }

    /** @test */
    public function a_role_required_label()
    {
        $this->signInWithPermissions('create role');

        $role = $this->make(Role::class, ['label' => null]);

        $this->post(route('admin.role.store'), $role->toArray())
            ->assertSessionHasErrors('label');
    }

    /** @test */
    public function on_creating_role_slug_should_be_generated()
    {
        $this->withoutExceptionHandling();

        $role = $this->create(Role::class, ['label' => 'role label']);

        $this->assertEquals('role.label', $role->slug);
    }

    /** @test */
    public function a_role_label_should_be_unique()
    {
        $this->signInWithPermissions('create role');

        $adminRole = $this->create(Role::class, ['label' => 'admin role']);

        $this->assertEquals($adminRole->slug, 'admin.role');

        $roleTwo = $this->create(Role::class, ['label' => 'admin role']);

        $this->assertEquals($roleTwo->slug, 'admin.role.1');

        $role = $this->make(Role::class, ['label' => 'admin Role']);

        $this->post(route('admin.role.store'), $role->toArray())
            ->assertSessionHasErrors('label');

    }

    /** @test */
    public function a_role_should_be_assignable_to_user()
    {
        $this->withoutExceptionHandling();

        $adminRole = $this->create(Role::class, ['label' => 'admin']);

        $user = $this->signInConfirmed();

        $user->assignRole($adminRole);

        $this->assertTrue($user->hasRole($adminRole));
    }

    /** @test */
    public function unauthorized_user_cannot_perform_crud_on_roles()
    {
        $this->signInConfirmed();

        $role = $this->make(Role::class, ['label' => 'test label']);

        $this->post(route('admin.role.store'), $role->toArray())
            ->assertStatus(403);

        $role = $this->create(Role::class, ['label' => 'test label']);

        $this->patch(route('admin.role.update', $role->id), array_merge($role->toArray(), ['description' => 'description']))
            ->assertStatus(403);

        $this->delete(route('admin.role.destroy', $role->id))
            ->assertStatus(403);
    }

    /** @test */
    public function ony_authorized_user_can_perform_crud_on_roles()
    {
        $this->withoutExceptionHandling();

        $this->signInWithPermissions(['manage acl']);

        $role = $this->make(Role::class, ['label' => 'test label']);

        $expectecSlug = str_slug($role['label'], '.');

        $this->post(route('admin.role.store'), $role->toArray());

        $roleFromDatabase = Role::whereSlug($expectecSlug)->firstOrFail();

        $this->assertEquals($expectecSlug, $roleFromDatabase->slug);

        $this->patch(route('admin.role.update', $roleFromDatabase->id), [
            'label'       => 'New Label',
            'description' => 'new description'
        ]);

        $this->assertEquals('new label', $roleFromDatabase->fresh()->label);

        $this->delete(route('admin.role.destroy', $roleFromDatabase->id))->assertRedirect(route('admin.role.index'));
    }


    /**
     * @param $response
     * @return array
     */
    public function getRoleFromResponse($response): array
    {
        return json_decode($response->getContent(), 1);
    }

}
