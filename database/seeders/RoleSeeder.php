<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Schema::disableForeignKeyConstraints();
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        Schema::enableForeignKeyConstraints();

        $roleAdmin = Role::create(['name' => 'admin']);
        $roleOperator = Role::create(['name' => 'operator']);
        $roleForwarder = Role::create(['name' => 'forwarder']);

        Permission::create(['name' => 'edit_dispositions']);
        Permission::create(['name' => 'add_dispositions']);
        Permission::create(['name' => 'view_dispositions']);

        Permission::create(['name' => 'view_storage_yards']);
        Permission::create(['name' => 'edit_storage_yards']);

        Permission::create(['name' => 'view_operations']);

        Permission::create(['name' => 'view_transshipment_cards']);
        Permission::create(['name' => 'edit_transshipment_cards']);

        Permission::create(['name' => 'view_users']);

        $roleAdmin->givePermissionTo('edit_dispositions');
        $roleAdmin->givePermissionTo('add_dispositions');
        $roleAdmin->givePermissionTo('view_dispositions');

        $roleAdmin->givePermissionTo('view_storage_yards');
        $roleAdmin->givePermissionTo('edit_storage_yards');

        $roleAdmin->givePermissionTo('view_operations');

        $roleAdmin->givePermissionTo('view_transshipment_cards');
        $roleAdmin->givePermissionTo('edit_transshipment_cards');

        $roleAdmin->givePermissionTo('view_users');

        $roleOperator->givePermissionTo('view_operations');

        $roleForwarder->givePermissionTo('edit_dispositions');
        $roleForwarder->givePermissionTo('add_dispositions');
        $roleForwarder->givePermissionTo('view_dispositions');

        $roleForwarder->givePermissionTo('view_transshipment_cards');
        $roleForwarder->givePermissionTo('edit_transshipment_cards');

        $adminUser = User::where('email', 'admin@admin.com')->first();
        if ($adminUser) {
            $adminUser->assignRole('admin');
            $adminUser->assignRole('operator');
            $adminUser->assignRole('forwarder');
        }
    }
}
