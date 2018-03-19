<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = factory(\App\Models\User::class)
            ->create([
                'name'           => 'Admin',
                'email'          => 'admin@email.com',
                'password'       => Illuminate\Support\Facades\Hash::make('password'),
                'email_verified' => true
            ]);

        $this->makeAdminTrulyAdmin($admin);

    }

    protected function makeAdminTrulyAdmin($admin)
    {
        $rolesIds = \App\Models\Role::get()->pluck('id')->toArray();

        $admin->syncRoles($rolesIds);
    }
}
