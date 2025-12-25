<?php

namespace Database\Seeders;

use App\Models\Sender;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $senders = [
            [
                'full_name' => 'Ahmed Osama',
                'email' => 'ahmed@example.com',
                'phone' => '+96170123456',
                'password' => Hash::make('password'),
                'type' => 'sender',
                'status' => 'active',
                'is_verified' => true,
                'email_verified_at' => now(),
            ],
            [
                'full_name' => 'Sarah Ali',
                'email' => 'sarah@example.com',
                'phone' => '+96170123457',
                'password' => Hash::make('password'),
                'type' => 'sender',
                'status' => 'active',
                'is_verified' => true,
                'email_verified_at' => now(),
            ],
            [
                'full_name' => 'Mohammed Hassan',
                'email' => 'mohammed@example.com',
                'phone' => '+96170123458',
                'password' => Hash::make('password'),
                'type' => 'traveler',
                'status' => 'active',
                'is_verified' => true,
                'email_verified_at' => now(),
            ],
            [
                'full_name' => 'Layla Khoury',
                'email' => 'layla@example.com',
                'phone' => '+96170123459',
                'password' => Hash::make('password'),
                'type' => 'sender',
                'status' => 'active',
                'is_verified' => true,
                'email_verified_at' => now(),
            ],
            [
                'full_name' => 'Omar Fadel',
                'email' => 'omar@example.com',
                'phone' => '+96170123460',
                'password' => Hash::make('password'),
                'type' => 'traveler',
                'status' => 'active',
                'is_verified' => true,
                'email_verified_at' => now()->subDays(10),
            ],
            [
                'full_name' => 'Nour Ibrahim',
                'email' => 'nour@example.com',
                'phone' => '+96170123461',
                'password' => Hash::make('password'),
                'type' => 'sender',
                'status' => 'active',
                'is_verified' => true,
                'email_verified_at' => now(),
            ],
            [
                'full_name' => 'Khalil Mansour',
                'email' => 'khalil@example.com',
                'phone' => '+96170123462',
                'password' => Hash::make('password'),
                'type' => 'traveler',
                'status' => 'inactive',
                'is_verified' => false,
                'email_verified_at' => null,
            ],
            [
                'full_name' => 'Rania Saad',
                'email' => 'rania@example.com',
                'phone' => '+96170123463',
                'password' => Hash::make('password'),
                'type' => 'sender',
                'status' => 'active',
                'is_verified' => true,
                'email_verified_at' => now(),
            ],
            [
                'full_name' => 'Hassan Youssef',
                'email' => 'hassan@example.com',
                'phone' => '+96170123464',
                'password' => Hash::make('password'),
                'type' => 'traveler',
                'status' => 'active',
                'is_verified' => true,
                'email_verified_at' => now(),
            ],
            [
                'full_name' => 'Maya Karam',
                'email' => 'maya@example.com',
                'phone' => '+96170123465',
                'password' => Hash::make('password'),
                'type' => 'traveler',
                'status' => 'active',
                'is_verified' => true,
                'email_verified_at' => now(),
            ],
        ];

        foreach ($senders as $senderData) {
            Sender::firstOrCreate(
                ['email' => $senderData['email']],
                $senderData
            );
        }

        $this->command->info('Senders seeded successfully!');
    }
}

