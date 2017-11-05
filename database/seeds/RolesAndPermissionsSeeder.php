<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
    * Run the database seeds (php artisan db:seed).
    *
    * @return void
    */
    public function run()
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // create permissions
        Permission::updateOrCreate(['name' => 'manage patron categories']);
        Permission::updateOrCreate(['name' => 'manage users']);

        // create roles and assign existing permissions
        $role = Role::updateOrCreate(['name' => 'admin']);
        $this->checkAndAllow($role, 'manage patron categories');
        $this->checkAndAllow($role, 'manage users');

        $role = Role::updateOrCreate(['name' => 'standard']);
    }

    /**
     * Check if the role already has a permission and skip it if it does.
     * This avoids throwing an exception and stopping the seeding process.
     *
     * @param  Role   $role
     * @param  string $permissions
     *
     * @return bool
     */
    protected function checkAndAllow(Role $role, $permissions)
    {
        if (! $role->hasPermissionTo($permissions)) {
            $role->givePermissionTo($permissions);
        }

        return true;
    }
}
