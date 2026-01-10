<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['name' => 'Egypt', 'iso2' => 'EG'],
            ['name' => 'Saudi Arabia', 'iso2' => 'SA'],
            ['name' => 'United Arab Emirates', 'iso2' => 'AE'],
            ['name' => 'Kuwait', 'iso2' => 'KW'],
            ['name' => 'Qatar', 'iso2' => 'QA'],
            ['name' => 'Bahrain', 'iso2' => 'BH'],
            ['name' => 'Oman', 'iso2' => 'OM'],
            ['name' => 'Lebanon', 'iso2' => 'LB'],
            ['name' => 'Jordan', 'iso2' => 'JO'],
            ['name' => 'United Kingdom', 'iso2' => 'GB'],
            ['name' => 'United States', 'iso2' => 'US'],
            ['name' => 'Germany', 'iso2' => 'DE'],
            ['name' => 'France', 'iso2' => 'FR'],
            ['name' => 'Italy', 'iso2' => 'IT'],
            ['name' => 'Spain', 'iso2' => 'ES'],
        ];

        foreach ($countries as $country) {
            Country::firstOrCreate(
                ['name' => $country['name']],
                $country
            );
        }
    }
}
