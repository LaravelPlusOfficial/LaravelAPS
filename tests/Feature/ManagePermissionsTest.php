<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManagePermissionsTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function guest_users_cannot_perform_create_and_delete_operations_on_permissions()
    {
        $this->get(route('admin.permission.index'))->assertRedirect('/login');
        $this->post(route('admin.permission.store'), [])->assertRedirect('/login');
        $this->delete(route('admin.permission.destroy', 'permission-slug'))->assertRedirect('/login');
    }


    /** @test */
    public function permissions_can_be_created()
    {
        $this->withoutExceptionHandling();

        $this->signInWithPermissions(['manage acl']);

        $permission = $this->make(Permission::class);

        $this->post(route('admin.permission.store'), $permission->toArray());

        $permissionFromDatabase = Permission::whereSlug(str_slug($permission['label'], '.'))->firstOrFail();

        $this->assertEquals($permission['description'], $permissionFromDatabase->description);

    }


    /** @test */
    public function a_permission_required_label()
    {
        $this->createPermission(['label' => null])
            ->assertSessionHasErrors('label');
    }

    /** @test */
    public function on_creating_permission_slug_should_be_generated()
    {
        $permission = $this->create(Permission::class, ['label' => 'permission label']);

        $this->assertEquals('permission.label', $permission->slug);
    }

    /** @test */
    public function a_permission_slug_should_be_unique()
    {
        $editPosts = $this->create(Permission::class, ['label' => 'edit posts']);

        $this->assertEquals($editPosts->slug, 'edit.posts');

        $editPostsTwo = $this->create(Permission::class, ['label' => 'edit posts']);

        $this->assertEquals($editPostsTwo->slug, 'edit.posts.1');

        $permission = $this->make(Permission::class, ['label' => 'edit posts']);

        $this->signInWithPermissions(['create permission']);

        $this->post(route('admin.permission.store'), $permission->toArray())
            ->assertSessionHasErrors('label');

    }


    /** @test */
    public function a_permission_should_be_attachable_to_role()
    {
        $this->signInConfirmed();

        $adminRole = $this->create(Role::class, ['label' => 'admin']);

        $editPostsPermission = $this->create(Permission::class, ['label' => 'edit posts']);

        $adminRole->attachPermission($editPostsPermission);

        $this->assertEquals($editPostsPermission->slug, $adminRole->fresh(['permissions'])->permissions->first()->slug);
    }

    /** @test */
    public function a_user_can_be_checked_for_permission()
    {
        $this->withoutExceptionHandling();

        $editPostsPermission = $this->create(Permission::class, ['label' => 'edit posts']);
        $viewPostsPermission = $this->create(Permission::class, ['label' => 'view posts']);

        $user = $this->signInWithPermissions([$editPostsPermission, $viewPostsPermission]);

        $this->assertTrue($user->can('edit.posts'));

    }

    /** @test */
    public function freezed_permissions_should_not_be_update_or_delete()
    {
        $this->signInWithPermissions(['manage acl', 'manage users']);

        $nonEditablePermission = Permission::whereSlug('manage.users')->first();

        $this->delete(route('admin.permission.destroy', $nonEditablePermission->id))
            ->assertStatus(403);

    }

    protected function createPermission($overrides = [])
    {
        $this->signInWithPermissions(['create permission']);

        $permission = $this->make(Permission::class, $overrides);

        return $this->post(route('admin.permission.store'), $permission->toArray());
    }


}
