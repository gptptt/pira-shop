<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for users
        $userPermissions = [
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            'manage-users',
        ];

        // Create permissions for products and pricing
        $productPermissions = [
            'view-products',
            'create-products',
            'edit-products',
            'delete-products',
            'manage-products',
        ];

        // Create permissions for orders
        $orderPermissions = [
            'view-orders',
            'create-orders',
            'edit-orders',
            'delete-orders',
            'manage-orders',
        ];
        
        // Create permissions for subscriptions
        $subscriptionPermissions = [
            'view-subscriptions',
            'create-subscriptions',
            'edit-subscriptions',
            'cancel-subscriptions',
            'manage-subscriptions',
        ];
        
        // Create permissions for content management
        $contentPermissions = [
            'view-posts',
            'create-posts',
            'edit-posts',
            'delete-posts',
            'publish-posts',
            'manage-posts',
        ];
        
        // Create permissions for transcripts
        $transcriptPermissions = [
            'view-own-transcripts',
            'create-transcripts',
            'edit-own-transcripts',
            'delete-own-transcripts',
            'view-all-transcripts',
            'edit-all-transcripts',
            'delete-all-transcripts',
            'manage-transcripts',
        ];
        
        // Create permissions for reports and dashboard
        $reportPermissions = [
            'view-reports',
            'view-dashboard',
            'view-sales-dashboard',
            'view-admin-dashboard',
        ];
        
        // Create permissions for settings
        $settingPermissions = [
            'view-settings',
            'edit-settings',
            'manage-settings',
        ];

        // Combined array of all permissions
        $allPermissions = array_merge(
            $userPermissions,
            $productPermissions, 
            $orderPermissions,
            $subscriptionPermissions,
            $contentPermissions,
            $transcriptPermissions,
            $reportPermissions,
            $settingPermissions
        );

        // Create permissions if they don't exist
        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Admin role if it doesn't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        
        // Give all permissions to admin role
        $adminRole->syncPermissions(Permission::all());

        // Create Sales role if it doesn't exist
        $salesRole = Role::firstOrCreate(['name' => 'sales']);
        
        // Give specific permissions to sales role
        $salesPermissions = [
            'view-users',
            'view-products',
            'view-orders', 'create-orders', 'edit-orders',
            'view-subscriptions', 'create-subscriptions', 'edit-subscriptions',
            'view-own-transcripts',
            'view-reports', 'view-dashboard', 'view-sales-dashboard',
        ];
        $salesRole->syncPermissions($salesPermissions);

        // Create Customer role if it doesn't exist
        $customerRole = Role::firstOrCreate(['name' => 'customer']);
        
        // Give specific permissions to customer role
        $customerPermissions = [
            'view-own-transcripts', 'create-transcripts', 'edit-own-transcripts', 'delete-own-transcripts',
            'view-products',
            'create-orders',
            'view-subscriptions',
        ];
        $customerRole->syncPermissions($customerPermissions);

        // Create Editor role if it doesn't exist
        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        
        // Give specific permissions to editor role
        $editorPermissions = [
            'view-posts', 'create-posts', 'edit-posts', 'delete-posts',
            'view-reports',
        ];
        $editorRole->syncPermissions($editorPermissions);

        $this->command->info('Roles and Permissions have been created successfully!');
    }
}
