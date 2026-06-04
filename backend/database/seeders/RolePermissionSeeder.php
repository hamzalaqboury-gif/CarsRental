<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'manage-users',
            'manage-vehicles',
            'manage-reservations',
            'view-dashboard',
            'make-reservations',
            'manage-payments',
            'view-reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'api']);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'api']);
        $admin      = Role::firstOrCreate(['name' => 'admin',       'guard_name' => 'api']);
        $manager    = Role::firstOrCreate(['name' => 'manager',     'guard_name' => 'api']);
        $client     = Role::firstOrCreate(['name' => 'client',      'guard_name' => 'api']);

        $superAdmin->syncPermissions($permissions);
        $admin->syncPermissions(['manage-users', 'manage-vehicles', 'manage-reservations', 'view-dashboard', 'view-reports', 'manage-payments']);
        $manager->syncPermissions(['manage-vehicles', 'manage-reservations', 'view-dashboard', 'view-reports']);
        $client->syncPermissions(['make-reservations', 'view-dashboard']);

        // Super Admin user
        $superAdminUser = User::firstOrCreate(
            ['email' => 'superadmin@carsrental.com'],
            [
                'name'     => 'Super Admin',
                'password' => bcrypt('SuperAdmin@123'),
                'phone'    => '+1234567890',
            ]
        );
        $superAdminUser->syncRoles(['super-admin']);

        // Admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@carsrental.com'],
            [
                'name'     => 'Admin User',
                'password' => bcrypt('Admin@123456'),
                'phone'    => '+1234567891',
            ]
        );
        $adminUser->syncRoles(['admin']);

        // Manager user
        $managerUser = User::firstOrCreate(
            ['email' => 'manager@carsrental.com'],
            [
                'name'     => 'Fleet Manager',
                'password' => bcrypt('Manager@123'),
                'phone'    => '+1234567892',
            ]
        );
        $managerUser->syncRoles(['manager']);

        // Client user
        $clientUser = User::firstOrCreate(
            ['email' => 'client@carsrental.com'],
            [
                'name'     => 'John Client',
                'password' => bcrypt('Client@123456'),
                'phone'    => '+1234567893',
                'address'  => '123 Main St, New York, NY',
            ]
        );
        $clientUser->syncRoles(['client']);

        $this->command->info('Roles, permissions, and default users created.');
    }
}
