<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define all permissions
        $permissions = [
            // User Management
            ['name' => 'View Users', 'slug' => 'view-users'],
            ['name' => 'Create Users', 'slug' => 'create-users'],
            ['name' => 'Update Users', 'slug' => 'update-users'],
            ['name' => 'Delete Users', 'slug' => 'delete-users'],

            // Role Management
            ['name' => 'View Roles', 'slug' => 'view-roles'],
            ['name' => 'Create Roles', 'slug' => 'create-roles'],
            ['name' => 'Update Roles', 'slug' => 'update-roles'],
            ['name' => 'Delete Roles', 'slug' => 'delete-roles'],

            // Permission Management
            ['name' => 'View Permissions', 'slug' => 'view-permissions'],
            ['name' => 'Create Permissions', 'slug' => 'create-permissions'],
            ['name' => 'Update Permissions', 'slug' => 'update-permissions'],
            ['name' => 'Delete Permissions', 'slug' => 'delete-permissions'],

            // Page Management
            ['name' => 'View Pages', 'slug' => 'view-pages'],
            ['name' => 'Create Pages', 'slug' => 'create-pages'],
            ['name' => 'Update Pages', 'slug' => 'update-pages'],
            ['name' => 'Delete Pages', 'slug' => 'delete-pages'],

            // Settings Management
            ['name' => 'View Settings', 'slug' => 'view-settings'],
            ['name' => 'Update Settings', 'slug' => 'update-settings'],

            // City Management
            ['name' => 'View Cities', 'slug' => 'view-cities'],
            ['name' => 'Create Cities', 'slug' => 'create-cities'],
            ['name' => 'Update Cities', 'slug' => 'update-cities'],
            ['name' => 'Delete Cities', 'slug' => 'delete-cities'],

            // Package Type Management
            ['name' => 'View Package Types', 'slug' => 'view-package-types'],
            ['name' => 'Create Package Types', 'slug' => 'create-package-types'],
            ['name' => 'Update Package Types', 'slug' => 'update-package-types'],
            ['name' => 'Delete Package Types', 'slug' => 'delete-package-types'],

            // Package Management
            ['name' => 'View Packages', 'slug' => 'view-packages'],
            ['name' => 'Create Packages', 'slug' => 'create-packages'],
            ['name' => 'Update Packages', 'slug' => 'update-packages'],
            ['name' => 'Delete Packages', 'slug' => 'delete-packages'],

            // Sender Management
            ['name' => 'View Senders', 'slug' => 'view-senders'],
            ['name' => 'Create Senders', 'slug' => 'create-senders'],
            ['name' => 'Update Senders', 'slug' => 'update-senders'],
            ['name' => 'Delete Senders', 'slug' => 'delete-senders'],

            // Traveler Ticket Management
            ['name' => 'View Traveler Tickets', 'slug' => 'view-traveler-tickets'],
            ['name' => 'Create Traveler Tickets', 'slug' => 'create-traveler-tickets'],
            ['name' => 'Update Traveler Tickets', 'slug' => 'update-traveler-tickets'],
            ['name' => 'Delete Traveler Tickets', 'slug' => 'delete-traveler-tickets'],
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        $this->command->info('Permissions seeded successfully!');
        $this->command->info('Created ' . count($permissions) . ' permissions');
    }
}
