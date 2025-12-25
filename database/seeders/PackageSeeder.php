<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\PackageType;
use App\Models\Sender;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $senders = Sender::where('status', 'active')->get();
        $packageTypes = PackageType::where('is_active', true)->get();

        if ($senders->isEmpty() || $packageTypes->isEmpty()) {
            $this->command->warn('No active senders or package types found. Please run SenderSeeder and PackageTypeSeeder first.');
            return;
        }

        $statuses = ['pending_review', 'approved', 'paid', 'in_transit', 'delivered', 'cancelled'];
        $cities = ['Beirut', 'Tripoli', 'Sidon', 'Tyre', 'Zahle', 'Jounieh', 'Byblos', 'Baalbek'];

        $packages = [];

        // Create packages for each sender
        foreach ($senders->take(5) as $sender) {
            $packageCount = rand(2, 5);
            
            for ($i = 0; $i < $packageCount; $i++) {
                $pickupCity = $cities[array_rand($cities)];
                $deliveryCity = $cities[array_rand($cities)];
                
                // Ensure delivery city is different from pickup
                while ($deliveryCity === $pickupCity) {
                    $deliveryCity = $cities[array_rand($cities)];
                }

                $pickupDate = Carbon::now()->addDays(rand(1, 7));
                $deliveryDate = $pickupDate->copy()->addDays(rand(1, 3));
                
                $packageType = $packageTypes->random();
                $status = $statuses[array_rand($statuses)];

                $packages[] = [
                    'sender_id' => $sender->id,
                    'pickup_address_id' => $sender->addresses()->inRandomOrder()->first()?->id,
                    'pickup_full_address' => $this->generateAddress($pickupCity),
                    'pickup_country' => 'Lebanon',
                    'pickup_city' => $pickupCity,
                    'pickup_area' => $this->generateArea(),
                    'pickup_landmark' => rand(0, 1) ? 'Near ' . $this->generateLandmark() : null,
                    'pickup_latitude' => 33.8 + (rand(0, 20) / 100),
                    'pickup_longitude' => 35.4 + (rand(0, 30) / 100),
                    'pickup_date' => $pickupDate,
                    'pickup_time' => Carbon::createFromTime(rand(8, 18), rand(0, 59))->format('H:i:s'),
                    'delivery_full_address' => $this->generateAddress($deliveryCity),
                    'delivery_country' => 'Lebanon',
                    'delivery_city' => $deliveryCity,
                    'delivery_area' => $this->generateArea(),
                    'delivery_landmark' => rand(0, 1) ? 'Near ' . $this->generateLandmark() : null,
                    'delivery_latitude' => 33.8 + (rand(0, 20) / 100),
                    'delivery_longitude' => 35.4 + (rand(0, 30) / 100),
                    'delivery_date' => $deliveryDate,
                    'delivery_time' => Carbon::createFromTime(rand(9, 19), rand(0, 59))->format('H:i:s'),
                    'receiver_name' => $this->generateName(),
                    'receiver_mobile' => '+961' . rand(3, 7) . str_pad(rand(0, 9999999), 7, '0', STR_PAD_LEFT),
                    'receiver_notes' => rand(0, 1) ? 'Please call before delivery.' : null,
                    'package_type_id' => $packageType->id,
                    'description' => $this->generateDescription(),
                    'weight' => round(rand(1, 5000) / 100, 2), // 0.01 to 50 kg
                    'length' => rand(10, 100),
                    'width' => rand(10, 100),
                    'height' => rand(10, 100),
                    'special_instructions' => rand(0, 1) ? 'Fragile - handle with care.' : null,
                    'status' => $status,
                    'compliance_confirmed' => true,
                    'created_at' => Carbon::now()->subDays(rand(0, 30)),
                    'updated_at' => Carbon::now()->subDays(rand(0, 30)),
                ];
            }
        }

        foreach ($packages as $packageData) {
            Package::create($packageData);
        }

        $this->command->info('Packages seeded successfully!');
    }

    private function generateAddress(string $city): string
    {
        $streets = ['Main Street', 'Bliss Street', 'Hamra Street', 'Clemenceau Street', 'Industrial Zone', 'Airport Road'];
        $buildings = ['Plaza', 'Tower', 'Building', 'Center', 'Complex'];
        
        $street = $streets[array_rand($streets)];
        $building = $buildings[array_rand($buildings)];
        $floor = rand(1, 10);
        
        return "{$street}, {$building} {$floor}, {$city}";
    }

    private function generateArea(): string
    {
        $areas = ['Hamra', 'Verdun', 'Achrafieh', 'Downtown', 'Mazraa', 'Badaro', 'Mina', 'Al Mina'];
        return $areas[array_rand($areas)];
    }

    private function generateLandmark(): string
    {
        $landmarks = ['AUB Main Gate', 'ABC Mall', 'City Center', 'Mall', 'University', 'Hospital', 'Mosque', 'Church'];
        return $landmarks[array_rand($landmarks)];
    }

    private function generateName(): string
    {
        $firstNames = ['Elie', 'Sarah', 'Mohammad', 'Layla', 'Khaled', 'Nour', 'Ahmad', 'Fatima', 'Hassan', 'Maya'];
        $lastNames = ['Haddad', 'Youssef', 'Khalil', 'Ibrahim', 'Mahmoud', 'Ali', 'Karam', 'Nasser', 'Saad', 'Fadel'];
        
        return $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
    }

    private function generateDescription(): string
    {
        $descriptions = [
            'Apple AirPods sealed box',
            'Documents - Legal papers',
            'Electronics - Laptop',
            'Clothing - Winter jacket',
            'Gift - Birthday present',
            'Food - Homemade sweets',
            'Books - University textbooks',
            'Personal items - Cosmetics',
            'Electronics - Smartphone',
            'Documents - Passport',
        ];
        
        return $descriptions[array_rand($descriptions)];
    }
}
