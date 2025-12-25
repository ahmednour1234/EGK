<?php

namespace Database\Seeders;

use App\Models\PackageType;
use Illuminate\Database\Seeder;

class PackageTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packageTypes = [
            [
                'name_en' => 'Clothes',
                'name_ar' => 'ملابس',
                'slug' => 'clothes',
                'color' => 'info',
                'description_en' => 'Clothing items and apparel',
                'description_ar' => 'الملابس والملابس',
                'order' => 1,
            ],
            [
                'name_en' => 'Documents',
                'name_ar' => 'مستندات',
                'slug' => 'documents',
                'color' => 'success',
                'description_en' => 'Important documents and papers',
                'description_ar' => 'المستندات والأوراق المهمة',
                'order' => 2,
            ],
            [
                'name_en' => 'Electronics',
                'name_ar' => 'إلكترونيات',
                'slug' => 'electronics',
                'color' => 'warning',
                'description_en' => 'Electronic devices and gadgets',
                'description_ar' => 'الأجهزة الإلكترونية والأدوات',
                'order' => 3,
            ],
            [
                'name_en' => 'Gifts',
                'name_ar' => 'هدايا',
                'slug' => 'gifts',
                'color' => 'primary',
                'description_en' => 'Gift items and presents',
                'description_ar' => 'عناصر الهدايا والهدايا',
                'order' => 4,
            ],
            [
                'name_en' => 'Food',
                'name_ar' => 'طعام',
                'slug' => 'food',
                'color' => 'danger',
                'description_en' => 'Food items and consumables',
                'description_ar' => 'مواد غذائية ومستهلكات',
                'order' => 5,
            ],
            [
                'name_en' => 'Personal Items',
                'name_ar' => 'أغراض شخصية',
                'slug' => 'personal-items',
                'color' => 'gray',
                'description_en' => 'Personal belongings and items',
                'description_ar' => 'الممتلكات الشخصية والأغراض',
                'order' => 6,
            ],
        ];

        foreach ($packageTypes as $type) {
            PackageType::firstOrCreate(
                ['slug' => $type['slug']],
                array_merge($type, ['is_active' => true])
            );
        }

        $this->command->info('Package types seeded successfully!');
    }
}

