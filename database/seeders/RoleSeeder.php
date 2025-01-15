<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleOperator = Role::create(['name' => 'operator']);
        $roleForwarder = Role::create(['name' => 'forwarder']);

        Permission::create(['name' => 'view_deposits']);
        Permission::create(['name' => 'delete_deposits']);
        Permission::create(['name' => 'edit_deposits']);

        Permission::create(['name' => 'edit_dispositions']);
        Permission::create(['name' => 'add_dispositions']);
        Permission::create(['name' => 'view_dispositions']);

        $roleAdmin->givePermissionTo('view_deposits');
        $roleAdmin->givePermissionTo('delete_deposits');
        $roleAdmin->givePermissionTo('edit_deposits');

        $roleAdmin->givePermissionTo('edit_dispositions');
        $roleAdmin->givePermissionTo('add_dispositions');
        $roleAdmin->givePermissionTo('view_dispositions');

        $roleOperator->givePermissionTo('view_deposits');

        $roleForwarder->givePermissionTo('view_deposits');

        $roleForwarder->givePermissionTo('edit_dispositions');
        $roleForwarder->givePermissionTo('add_dispositions');
        $roleForwarder->givePermissionTo('view_dispositions');

        $adminUser = User::where('email', 'admin@admin.com')->first();
        $adminUser->assignRole('admin');
        $adminUser->assignRole('operator');
        $adminUser->assignRole('forwarder');
    }
}
