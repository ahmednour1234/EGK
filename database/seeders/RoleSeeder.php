<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default roles
        $adminRole = Role::firstOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Administrator',
                'description' => 'Full system access with all permissions',
            ]
        );

        $userRole = Role::firstOrCreate(
            ['slug' => 'user'],
            [
                'name' => 'User',
                'description' => 'Standard user access with limited permissions',
            ]
        );

        $senderManagerRole = Role::firstOrCreate(
            ['slug' => 'sender-manager'],
            [
                'name' => 'Sender Manager',
                'description' => 'Can manage senders (view, update status, but not delete)',
            ]
        );

        // Package Manager Role - Full package management
        $packageManagerRole = Role::firstOrCreate(
            ['slug' => 'package-manager'],
            [
                'name' => 'Package Manager',
                'description' => 'Can manage packages (view, create, update, delete)',
            ]
        );

        // Ticket Manager Role - Full ticket management
        $ticketManagerRole = Role::firstOrCreate(
            ['slug' => 'ticket-manager'],
            [
                'name' => 'Ticket Manager',
                'description' => 'Can manage traveler tickets (view, create, update, delete)',
            ]
        );

        // Operations Manager Role - Can manage both packages and tickets
        $operationsManagerRole = Role::firstOrCreate(
            ['slug' => 'operations-manager'],
            [
                'name' => 'Operations Manager',
                'description' => 'Can manage packages and traveler tickets for matching operations',
            ]
        );

        // Package Operator Role - Limited package operations (view, update status)
        $packageOperatorRole = Role::firstOrCreate(
            ['slug' => 'package-operator'],
            [
                'name' => 'Package Operator',
                'description' => 'Can view and update package status, but cannot create or delete',
            ]
        );

        // Ticket Operator Role - Limited ticket operations (view, update status)
        $ticketOperatorRole = Role::firstOrCreate(
            ['slug' => 'ticket-operator'],
            [
                'name' => 'Ticket Operator',
                'description' => 'Can view and update ticket status, but cannot create or delete',
            ]
        );

        // Assign all permissions to admin role
        $adminRole->permissions()->sync(Permission::pluck('id'));

        // Assign sender management permissions to sender-manager role
        $senderPermissions = Permission::whereIn('slug', [
            'view-senders',
            'update-senders',
        ])->pluck('id');
        $senderManagerRole->permissions()->sync($senderPermissions);

        // Assign view-only sender permission to user role
        $viewSenderPermission = Permission::where('slug', 'view-senders')->first();
        if ($viewSenderPermission) {
            $userRole->permissions()->syncWithoutDetaching([$viewSenderPermission->id]);
        }

        // Assign package management permissions to package-manager role
        $packagePermissions = Permission::whereIn('slug', [
            'view-packages',
            'create-packages',
            'update-packages',
            'delete-packages',
        ])->pluck('id');
        $packageManagerRole->permissions()->sync($packagePermissions);

        // Assign ticket management permissions to ticket-manager role
        $ticketPermissions = Permission::whereIn('slug', [
            'view-traveler-tickets',
            'create-traveler-tickets',
            'update-traveler-tickets',
            'delete-traveler-tickets',
        ])->pluck('id');
        $ticketManagerRole->permissions()->sync($ticketPermissions);

        // Assign both package and ticket permissions to operations-manager role
        $operationsPermissions = Permission::whereIn('slug', [
            'view-packages',
            'create-packages',
            'update-packages',
            'delete-packages',
            'view-traveler-tickets',
            'create-traveler-tickets',
            'update-traveler-tickets',
            'delete-traveler-tickets',
        ])->pluck('id');
        $operationsManagerRole->permissions()->sync($operationsPermissions);

        // Assign limited package permissions to package-operator role
        $packageOperatorPermissions = Permission::whereIn('slug', [
            'view-packages',
            'update-packages',
        ])->pluck('id');
        $packageOperatorRole->permissions()->sync($packageOperatorPermissions);

        // Assign limited ticket permissions to ticket-operator role
        $ticketOperatorPermissions = Permission::whereIn('slug', [
            'view-traveler-tickets',
            'update-traveler-tickets',
        ])->pluck('id');
        $ticketOperatorRole->permissions()->sync($ticketOperatorPermissions);

        $this->command->info('Roles seeded successfully!');
        $this->command->info('Created roles:');
        $this->command->info('  - Admin (all permissions)');
        $this->command->info('  - User (view senders)');
        $this->command->info('  - Sender Manager (view & update senders)');
        $this->command->info('  - Package Manager (full package management)');
        $this->command->info('  - Ticket Manager (full ticket management)');
        $this->command->info('  - Operations Manager (packages + tickets)');
        $this->command->info('  - Package Operator (view & update packages)');
        $this->command->info('  - Ticket Operator (view & update tickets)');
    }
}
