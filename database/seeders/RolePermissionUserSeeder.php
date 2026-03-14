<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolePermissionUserSeeder extends Seeder
{
    public function run(): void
    {

        $permissions = [

            // Roles
            ['name' => 'view roles', 'slug' => 'role.view', 'group' => 'roles'],
            ['name' => 'create roles', 'slug' => 'role.create', 'group' => 'roles'],
            ['name' => 'update roles', 'slug' => 'role.update', 'group' => 'roles'],
            ['name' => 'delete roles', 'slug' => 'role.delete', 'group' => 'roles'],

            // Users
            ['name' => 'view users', 'slug' => 'user.view', 'group' => 'user'],
            ['name' => 'create users', 'slug' => 'user.create', 'group' => 'user'],
            ['name' => 'update users', 'slug' => 'user.update', 'group' => 'user'],
            ['name' => 'delete users', 'slug' => 'user.delete', 'group' => 'user'],

            // Categories
            ['name' => 'view categories', 'slug' => 'categories.view', 'group' => 'categories'],
            ['name' => 'create categories', 'slug' => 'categories.create', 'group' => 'categories'],
            ['name' => 'update categories', 'slug' => 'categories.update', 'group' => 'categories'],
            ['name' => 'delete categories', 'slug' => 'categories.delete', 'group' => 'categories'],

            // Products
            ['name' => 'view products', 'slug' => 'products.view', 'group' => 'products'],
            ['name' => 'create products', 'slug' => 'products.create', 'group' => 'products'],
            ['name' => 'update products', 'slug' => 'products.update', 'group' => 'products'],
            ['name' => 'delete products', 'slug' => 'products.delete', 'group' => 'products'],

            // Invoice
            ['name' => 'view invoice', 'slug' => 'invoice.view', 'group' => 'invoice'],
            ['name' => 'create invoice', 'slug' => 'invoice.create', 'group' => 'invoice'],
            ['name' => 'update invoice', 'slug' => 'invoice.update', 'group' => 'invoice'],
            ['name' => 'delete invoice', 'slug' => 'invoice.delete', 'group' => 'invoice'],

            // Reports
            ['name' => 'view reports', 'slug' => 'reports.view', 'group' => 'reports'],
            ['name' => 'create reports', 'slug' => 'reports.create', 'group' => 'reports'],
            ['name' => 'update reports', 'slug' => 'reports.update', 'group' => 'reports'],
            ['name' => 'delete reports', 'slug' => 'reports.delete', 'group' => 'reports'],
        ];

        // Insert permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['slug' => $permission['slug']], $permission);
        }

        // Create Admin Role
        $adminRole = Role::firstOrCreate([
            'slug' => 'admin'
        ], [
            'name' => 'Administrator',
            'description' => 'System Admin with full access'
        ]);

        $adminRole->permissions()->sync(Permission::pluck('id'));

        // Create Manager Role
        $managerRole = Role::firstOrCreate([
            'slug' => 'manager'
        ], [
            'name' => 'Manager',
            'description' => 'System Manager with limited access'
        ]);

        $managerPermissions = Permission::whereIn('slug', [
            'role.view',
            'user.view',
            'user.create',
            'products.view',
            'products.create',
            'invoice.view',
            'invoice.create',
            'reports.view',
            'reports.create',
        ])->pluck('id');

        $managerRole->permissions()->sync($managerPermissions);

        // Create User Role
        $userRole = Role::firstOrCreate([
            'slug' => 'user'
        ], [
            'name' => 'User',
            'description' => 'General system user'
        ]);

        $userPermissions = Permission::whereIn('slug', [
            'user.view',
            'products.view',
            'products.create',
            'invoice.view',
            'reports.view',
        ])->pluck('id');

        $userRole->permissions()->sync($userPermissions);

        // Create Admin User
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'phone' => '1234567',
            ]
        );

        $adminUser->roles()->sync([$adminRole->id]);
    }
}