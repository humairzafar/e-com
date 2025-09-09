<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['Admin', 'User','customer'];

        $adminPermissions = [
    // User
    'add-user',
    'edit-user',
    'delete-user',
    'view-user',
    'sidebar-user',

    // Product
    'create-product',
    'edit-product',
    'delete-product',
    'view-product',
    'sidebar-product',

    // Roles
    'add-roles',
    'edit-roles',
    'delete-roles',
    'view-roles',
    'sidebar-roles',

    // Department
    'add-department',
    'edit-department',
    'delete-department',
    'view-department',
    'sidebar-department',

    // Category
    'add-category',
    'edit-category',
    'delete-category',
    'view-category',
    'sidebar-category',

    // Sub Category
    'add-sub-category',
    'edit-sub-category',
    'delete-sub-category',
    'view-sub-category',
    'sidebar-sub-category',

    // Dashboard
    'view-dashboard',
    'sidebar-dashboard',

    // Profile
    'view-profile',
    'edit-profile',
    'change-password',
    'sidebar-profile',

    // Vehicle
    'add-vehicle',
    'edit-vehicle',
    'delete-vehicle',
    'view-vehicle',
    'sidebar-vehicle',

    // Vehicle Category
    'add-vehiclecategory',
    'edit-vehiclecategory',
    'delete-vehiclecategory',
    'view-vehiclecategory',
    'sidebar-vehiclecategory',

    // Data Import/Export
    'import-data',
    'export-data',

    // Employee
    'add-employee',
    'edit-employee',
    'delete-employee',
    'view-employee',
    'sidebar-employee',

    // Designation
    'add-designation',
    'edit-designation',
    'delete-designation',
    'view-designation',
    'sidebar-designation',

    // Location
    'add-location',
    'edit-location',
    'delete-location',
    'view-location',
    'sidebar-location',

    // Permissions
    'add-permissions',
    'edit-permissions',
    'delete-permissions',
    'view-permissions',
    'sidebar-permissions',

    // Parts
    'add-parts',
    'edit-parts',
    'delete-parts',
    'view-parts',
    'sidebar-parts',

    // Parts Category
    'add-parts-category',
    'edit-parts-category',
    'delete-parts-category',
    'view-parts-category',
    'sidebar-parts-category',

    // Town
    'add-town',
    'edit-town',
    'delete-town',
    'view-town',
    'sidebar-town',
];


        $userPermissions = [
            'delete-product',
            'view-product',
            'create-product',
            // Town
            'add-town',
            'edit-town',
            'delete-town',
            'view-town',
            'sidebar-town',
            'sidebar-employee',
            'view-employee',
            'add-employee',
            // 'edit-employee',
            // 'delete-employee',
        ];

        $commonPermissions = [

        ];

        // ✅ First create all permissions
        $allPermissions = array_unique(array_merge($adminPermissions, $userPermissions));
        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web']
            );
        }

        // ✅ Create roles
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // ✅ Assign permissions to roles
        $adminRole = Role::findByName('Admin');
        $adminRole->syncPermissions($adminPermissions);

        $userRole = Role::findByName('User');
        $userRole->syncPermissions($userPermissions);
    }
}
