<?php

namespace Database\Seeders;

use App\Models\PackageType;
use App\Models\Sender;
use App\Models\TravelerTicket;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TravelerTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get travelers (senders with type='traveler')
        $travelers = Sender::where('type', 'traveler')
            ->where('status', 'active')
            ->get();

        if ($travelers->isEmpty()) {
            $this->command->warn('No active travelers found. Please run SenderSeeder first.');
            return;
        }

        // Get package types for acceptable_package_types
        $packageTypes = PackageType::where('is_active', true)->pluck('id')->toArray();

        if (empty($packageTypes)) {
            $this->command->warn('No active package types found. Please run PackageTypeSeeder first.');
            return;
        }

        // Lebanese cities
        $cities = [
            'Beirut', 'Tripoli', 'Sidon', 'Tyre', 'Zahle', 'Jounieh', 
            'Byblos', 'Baalbek', 'Nabatieh', 'Batroun', 'Aley', 'Zgharta'
        ];

        // Transport types
        $transportTypes = ['Car', 'Truck', 'Van', 'SUV', 'Motorcycle'];

        // Statuses
        $statuses = ['draft', 'active', 'matched', 'completed', 'cancelled'];

        $tickets = [];

        // Create tickets for each traveler
        foreach ($travelers as $traveler) {
            $ticketCount = rand(1, 3);
            
            for ($i = 0; $i < $ticketCount; $i++) {
                $fromCity = $cities[array_rand($cities)];
                $toCity = $cities[array_rand($cities)];
                
                // Ensure to city is different from from city
                while ($toCity === $fromCity) {
                    $toCity = $cities[array_rand($cities)];
                }

                $tripType = rand(0, 1) ? 'one-way' : 'round-trip';
                $departureDate = Carbon::now()->addDays(rand(1, 14));
                $departureTime = Carbon::createFromTime(rand(6, 20), rand(0, 59))->format('H:i:s');
                
                $returnDate = null;
                $returnTime = null;
                if ($tripType === 'round-trip') {
                    $returnDate = $departureDate->copy()->addDays(rand(1, 7));
                    $returnTime = Carbon::createFromTime(rand(8, 22), rand(0, 59))->format('H:i:s');
                }

                $status = $statuses[array_rand($statuses)];
                
                // Weight limits (in kg)
                $weightLimit = rand(50, 500); // 50kg to 500kg
                $maxPackages = rand(5, 20);
                
                // Acceptable package types (random selection)
                $acceptableTypes = [];
                $numTypes = rand(1, min(3, count($packageTypes)));
                $selectedTypes = array_rand($packageTypes, $numTypes);
                if (is_array($selectedTypes)) {
                    foreach ($selectedTypes as $index) {
                        $acceptableTypes[] = $packageTypes[$index];
                    }
                } else {
                    $acceptableTypes[] = $packageTypes[$selectedTypes];
                }

                $tickets[] = [
                    'traveler_id' => $traveler->id,
                    'from_city' => $fromCity,
                    'to_city' => $toCity,
                    'full_address' => $this->generateAddress($toCity),
                    'landmark' => rand(0, 1) ? $this->generateLandmark() : null,
                    'latitude' => 33.8 + (rand(0, 20) / 100),
                    'longitude' => 35.4 + (rand(0, 30) / 100),
                    'trip_type' => $tripType,
                    'departure_date' => $departureDate,
                    'departure_time' => $departureTime,
                    'return_date' => $returnDate,
                    'return_time' => $returnTime,
                    'transport_type' => $transportTypes[array_rand($transportTypes)],
                    'total_weight_limit' => $weightLimit,
                    'max_package_count' => $maxPackages,
                    'acceptable_package_types' => $acceptableTypes,
                    'preferred_pickup_area' => rand(0, 1) ? $this->generateArea() : null,
                    'preferred_delivery_area' => rand(0, 1) ? $this->generateArea() : null,
                    'notes_for_senders' => rand(0, 1) ? $this->generateNotes() : null,
                    'allow_urgent_packages' => rand(0, 1),
                    'accept_only_verified_senders' => rand(0, 1),
                    'status' => $status,
                    'created_at' => Carbon::now()->subDays(rand(0, 30)),
                    'updated_at' => Carbon::now()->subDays(rand(0, 30)),
                ];
            }
        }

        // Create additional active tickets for matching
        $activeTravelers = $travelers->random(min(3, $travelers->count()));
        foreach ($activeTravelers as $traveler) {
            $fromCity = $cities[array_rand($cities)];
            $toCity = $cities[array_rand($cities)];
            
            while ($toCity === $fromCity) {
                $toCity = $cities[array_rand($cities)];
            }

            $departureDate = Carbon::now()->addDays(rand(1, 7));
            $departureTime = Carbon::createFromTime(rand(8, 18), rand(0, 59))->format('H:i:s');

            $acceptableTypes = [];
            $numTypes = rand(2, min(4, count($packageTypes)));
            $selectedTypes = array_rand($packageTypes, $numTypes);
            if (is_array($selectedTypes)) {
                foreach ($selectedTypes as $index) {
                    $acceptableTypes[] = $packageTypes[$index];
                }
            } else {
                $acceptableTypes[] = $packageTypes[$selectedTypes];
            }

            $tickets[] = [
                'traveler_id' => $traveler->id,
                'from_city' => $fromCity,
                'to_city' => $toCity,
                'full_address' => $this->generateAddress($toCity),
                'landmark' => rand(0, 1) ? $this->generateLandmark() : null,
                'latitude' => 33.8 + (rand(0, 20) / 100),
                'longitude' => 35.4 + (rand(0, 30) / 100),
                'trip_type' => 'one-way',
                'departure_date' => $departureDate,
                'departure_time' => $departureTime,
                'return_date' => null,
                'return_time' => null,
                'transport_type' => $transportTypes[array_rand($transportTypes)],
                'total_weight_limit' => rand(100, 300),
                'max_package_count' => rand(10, 15),
                'acceptable_package_types' => $acceptableTypes,
                'preferred_pickup_area' => $this->generateArea(),
                'preferred_delivery_area' => $this->generateArea(),
                'notes_for_senders' => 'Please ensure packages are properly sealed and labeled.',
                'allow_urgent_packages' => true,
                'accept_only_verified_senders' => false,
                'status' => 'active',
                'created_at' => Carbon::now()->subDays(rand(1, 5)),
                'updated_at' => Carbon::now()->subDays(rand(1, 5)),
            ];
        }

        // Create tickets
        foreach ($tickets as $ticketData) {
            TravelerTicket::create($ticketData);
        }

        $activeCount = count(array_filter($tickets, fn($t) => $t['status'] === 'active'));
        $matchedCount = count(array_filter($tickets, fn($t) => $t['status'] === 'matched'));
        
        $this->command->info('Traveler tickets seeded successfully!');
        $this->command->info('Created ' . count($tickets) . ' tickets');
        $this->command->info('  - Active: ' . $activeCount);
        $this->command->info('  - Matched: ' . $matchedCount);
        $this->command->info('  - Other statuses: ' . (count($tickets) - $activeCount - $matchedCount));
    }

    private function generateAddress(string $city): string
    {
        $streets = ['Main Street', 'Bliss Street', 'Hamra Street', 'Clemenceau Street', 'Airport Road', 'Highway'];
        $buildings = ['Plaza', 'Tower', 'Building', 'Center', 'Complex'];
        
        $street = $streets[array_rand($streets)];
        $building = $buildings[array_rand($buildings)];
        $number = rand(1, 500);
        
        return "{$number} {$street}, {$building}, {$city}";
    }

    private function generateArea(): string
    {
        $areas = ['Hamra', 'Verdun', 'Achrafieh', 'Downtown', 'Mazraa', 'Badaro', 'Mina', 'Al Mina', 'Jounieh', 'Zahle'];
        return $areas[array_rand($areas)];
    }

    private function generateLandmark(): string
    {
        $landmarks = [
            'AUB Main Gate',
            'ABC Mall',
            'City Center',
            'Beirut Mall',
            'American University',
            'Hospital',
            'Mosque',
            'Church',
            'Airport',
            'Port'
        ];
        return $landmarks[array_rand($landmarks)];
    }

    private function generateNotes(): string
    {
        $notes = [
            'Please ensure packages are properly sealed and labeled.',
            'I can accommodate urgent packages if needed.',
            'Contact me 24 hours before departure.',
            'I prefer lightweight packages only.',
            'Available for pickup in the morning.',
            'Can handle fragile items with care.',
            'Please coordinate delivery time in advance.',
        ];
        return $notes[array_rand($notes)];
    }
}
