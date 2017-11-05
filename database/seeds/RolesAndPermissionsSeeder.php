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
        // Permission::create(['name' => 'edit articles']);
        // Permission::create(['name' => 'delete articles']);
        // Permission::create(['name' => 'publish articles']);
        // Permission::create(['name' => 'unpublish articles']);

        // create roles and assign existing permissions
        $role = Role::updateOrCreate(['name' => 'admin']);
        // $role->givePermissionTo('edit articles');
        // $role->givePermissionTo('delete articles');

        $role = Role::updateOrCreate(['name' => 'standard']);
        // $role->givePermissionTo('publish articles');
        // $role->givePermissionTo('unpublish articles');
    }
}
