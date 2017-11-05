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
        $role->syncPermissions([
            'manage patron categories',
            'manage users'
        ]);

        $role = Role::updateOrCreate(['name' => 'standard']);
    }
}
