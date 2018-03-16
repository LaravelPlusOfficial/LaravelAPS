<?php

use Illuminate\Database\Seeder;

class AccessControlTableSeeder extends Seeder
{

    protected $roles = [
        'admin',
        'editor',
        'author',
        'contributor',
        'subscriber'
    ];

    protected $permissions = [
        'manage users',
        'manage acl',
        'manage settings',
        'manage posts',
        'manage pages',
        'manage queues',
        'manage taxonomy',
        'manage media',
        'manage comments',
        'create posts',
        'update posts',
        'delete posts',
        'add comment'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = $this->seedRoles();

        $permissionsIds = $this->seedPermissions();

        $roles['admin']->syncPermissions($permissionsIds);
    }

    protected function seedRoles()
    {
        $roles = [];

        foreach ($this->roles as $role) {

            $role = factory(\App\Models\Role::class)->create(['label' => $role]);

            $roles[$role->slug] = $role;
        }

        return $roles;
    }

    protected function seedPermissions()
    {
        $ids = [];

        foreach ($this->permissions as $label) {
            $ids[] = factory(\App\Models\Permission::class)->create(['label' => $label])->id;
        }

        return $ids;
    }

    /**
     * @return array
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * @param $labels
     */
    protected function createPermission($labels)
    {
        if (is_array($labels)) {
            foreach ($labels as $label) {
                factory(\App\Models\Permission::class)->create(['label' => $label]);
            }
        }

        if (is_string($labels)) {
            factory(\App\Models\Permission::class)->create(['label' => $labels]);
        }
    }

    public function getRoles()
    {
        return $this->roles;
    }
}
