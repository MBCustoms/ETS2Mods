<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'view mods',
            'create mods',
            'edit own mods',
            'edit any mods',
            'delete own mods',
            'delete any mods',
            'approve mods',
            'reject mods',
            'feature mods',
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            'view users',
            'edit users',
            'ban users',
            'view reports',
            'resolve reports',
            'view settings',
            'edit settings',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $moderator = Role::create(['name' => 'moderator']);
        $moderator->givePermissionTo([
            'view mods',
            'edit any mods',
            'delete any mods',
            'approve mods',
            'reject mods',
            'feature mods',
            'view categories',
            'view users',
            'view reports',
            'resolve reports',
        ]);

        $user = Role::create(['name' => 'user']);
        $user->givePermissionTo([
            'view mods',
            'create mods',
            'edit own mods',
            'delete own mods',
            'view categories',
        ]);
    }
}
