<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            ['name_en' => 'Beirut', 'name_ar' => 'بيروت', 'code' => 'BEI', 'order' => 1],
            ['name_en' => 'Tripoli', 'name_ar' => 'طرابلس', 'code' => 'TRI', 'order' => 2],
            ['name_en' => 'Sidon', 'name_ar' => 'صيدا', 'code' => 'SID', 'order' => 3],
            ['name_en' => 'Tyre', 'name_ar' => 'صور', 'code' => 'TYR', 'order' => 4],
            ['name_en' => 'Byblos', 'name_ar' => 'جبيل', 'code' => 'BYB', 'order' => 5],
            ['name_en' => 'Zahle', 'name_ar' => 'زحلة', 'code' => 'ZAH', 'order' => 6],
            ['name_en' => 'Baalbek', 'name_ar' => 'بعلبك', 'code' => 'BAA', 'order' => 7],
            ['name_en' => 'Jounieh', 'name_ar' => 'جونيه', 'code' => 'JOU', 'order' => 8],
            ['name_en' => 'Nabatieh', 'name_ar' => 'النبطية', 'code' => 'NAB', 'order' => 9],
            ['name_en' => 'Batroun', 'name_ar' => 'البترون', 'code' => 'BAT', 'order' => 10],
            ['name_en' => 'Halba', 'name_ar' => 'حلبا', 'code' => 'HAL', 'order' => 11],
            ['name_en' => 'Marjayoun', 'name_ar' => 'مرجعيون', 'code' => 'MAR', 'order' => 12],
            ['name_en' => 'Jezzine', 'name_ar' => 'جزين', 'code' => 'JEZ', 'order' => 13],
            ['name_en' => 'Baakline', 'name_ar' => 'بعقلين', 'code' => 'BAK', 'order' => 14],
            ['name_en' => 'Aley', 'name_ar' => 'عاليه', 'code' => 'ALE', 'order' => 15],
            ['name_en' => 'Bhamdoun', 'name_ar' => 'بحمدون', 'code' => 'BHA', 'order' => 16],
            ['name_en' => 'Bcharre', 'name_ar' => 'بشري', 'code' => 'BCH', 'order' => 17],
            ['name_en' => 'Zgharta', 'name_ar' => 'زغرتا', 'code' => 'ZGH', 'order' => 18],
            ['name_en' => 'Amioun', 'name_ar' => 'أميون', 'code' => 'AMI', 'order' => 19],
            ['name_en' => 'Kfarhazir', 'name_ar' => 'كفر حزير', 'code' => 'KFA', 'order' => 20],
            ['name_en' => 'Douma', 'name_ar' => 'دوما', 'code' => 'DOU', 'order' => 21],
            ['name_en' => 'Bint Jbeil', 'name_ar' => 'بنت جبيل', 'code' => 'BIN', 'order' => 22],
            ['name_en' => 'Tebnine', 'name_ar' => 'تبنين', 'code' => 'TEB', 'order' => 23],
            ['name_en' => 'Rachaya', 'name_ar' => 'راشيا', 'code' => 'RAC', 'order' => 24],
            ['name_en' => 'Hasbaya', 'name_ar' => 'حاصبيا', 'code' => 'HAS', 'order' => 25],
            ['name_en' => 'Machghara', 'name_ar' => 'مشغرة', 'code' => 'MAC', 'order' => 26],
            ['name_en' => 'Chekka', 'name_ar' => 'شكا', 'code' => 'CHE', 'order' => 27],
        ];

        foreach ($cities as $city) {
            City::firstOrCreate(
                ['name_en' => $city['name_en']],
                array_merge($city, ['is_active' => true])
            );
        }

        $this->command->info('Lebanese cities seeded successfully!');
    }
}

