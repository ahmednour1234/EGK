<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Page;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default roles
        $adminRole = Role::firstOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Administrator',
                'description' => 'Full system access',
            ]
        );

        $userRole = Role::firstOrCreate(
            ['slug' => 'user'],
            [
                'name' => 'User',
                'description' => 'Standard user access',
            ]
        );

        $senderManagerRole = Role::firstOrCreate(
            ['slug' => 'sender-manager'],
            [
                'name' => 'Sender Manager',
                'description' => 'Can manage senders (view, update status, but not delete)',
            ]
        );

        // Create default permissions
        $permissions = [
            ['name' => 'View Users', 'slug' => 'view-users'],
            ['name' => 'Create Users', 'slug' => 'create-users'],
            ['name' => 'Update Users', 'slug' => 'update-users'],
            ['name' => 'Delete Users', 'slug' => 'delete-users'],
            ['name' => 'View Roles', 'slug' => 'view-roles'],
            ['name' => 'Create Roles', 'slug' => 'create-roles'],
            ['name' => 'Update Roles', 'slug' => 'update-roles'],
            ['name' => 'Delete Roles', 'slug' => 'delete-roles'],
            ['name' => 'View Permissions', 'slug' => 'view-permissions'],
            ['name' => 'Create Permissions', 'slug' => 'create-permissions'],
            ['name' => 'Update Permissions', 'slug' => 'update-permissions'],
            ['name' => 'Delete Permissions', 'slug' => 'delete-permissions'],
            ['name' => 'View Pages', 'slug' => 'view-pages'],
            ['name' => 'Create Pages', 'slug' => 'create-pages'],
            ['name' => 'Update Pages', 'slug' => 'update-pages'],
            ['name' => 'Delete Pages', 'slug' => 'delete-pages'],
            ['name' => 'View Settings', 'slug' => 'view-settings'],
            ['name' => 'Update Settings', 'slug' => 'update-settings'],
            ['name' => 'View Cities', 'slug' => 'view-cities'],
            ['name' => 'Create Cities', 'slug' => 'create-cities'],
            ['name' => 'Update Cities', 'slug' => 'update-cities'],
            ['name' => 'Delete Cities', 'slug' => 'delete-cities'],
            ['name' => 'View Package Types', 'slug' => 'view-package-types'],
            ['name' => 'Create Package Types', 'slug' => 'create-package-types'],
            ['name' => 'Update Package Types', 'slug' => 'update-package-types'],
            ['name' => 'Delete Package Types', 'slug' => 'delete-package-types'],
            ['name' => 'View Packages', 'slug' => 'view-packages'],
            ['name' => 'Create Packages', 'slug' => 'create-packages'],
            ['name' => 'Update Packages', 'slug' => 'update-packages'],
            ['name' => 'Delete Packages', 'slug' => 'delete-packages'],
            ['name' => 'View Senders', 'slug' => 'view-senders'],
            ['name' => 'Create Senders', 'slug' => 'create-senders'],
            ['name' => 'Update Senders', 'slug' => 'update-senders'],
            ['name' => 'Delete Senders', 'slug' => 'delete-senders'],
            ['name' => 'View Traveler Tickets', 'slug' => 'view-traveler-tickets'],
            ['name' => 'Create Traveler Tickets', 'slug' => 'create-traveler-tickets'],
            ['name' => 'Update Traveler Tickets', 'slug' => 'update-traveler-tickets'],
            ['name' => 'Delete Traveler Tickets', 'slug' => 'delete-traveler-tickets'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

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

        // Create default admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
            ]
        );

        // Create sender manager user
        $senderManager = User::firstOrCreate(
            ['email' => 'sender-manager@example.com'],
            [
                'name' => 'Sender Manager',
                'password' => Hash::make('password'),
                'role_id' => $senderManagerRole->id,
            ]
        );

        // Create default settings
        Setting::set('app_name', 'EGK', 'text', 'Application name');
        Setting::set('app_url', 'http://localhost:8009', 'url', 'Application URL');
        Setting::set('app_email', 'info@egk.com', 'email', 'Application contact email');
        Setting::set('app_phone', '+1 (555) 123-4567', 'text', 'Application contact phone');
        Setting::set('api_base_url', 'http://localhost:8009/api', 'url', 'API base URL for documentation');
        Setting::set('ar_enabled', '1', 'text', 'Enable Arabic language support');

        // Create default pages
        Page::firstOrCreate(
            ['slug' => 'about'],
            [
                'title_en' => 'About Us',
                'title_ar' => 'من نحن',
                'content_en' => '<h2>Welcome to EGK</h2><p>This is the about us page. You can edit this content through the admin panel.</p><p>EGK is a modern application designed to meet your needs.</p>',
                'content_ar' => '<h2>مرحباً بكم في EGK</h2><p>هذه هي صفحة من نحن. يمكنك تعديل هذا المحتوى من خلال لوحة التحكم.</p><p>EGK هو تطبيق حديث مصمم لتلبية احتياجاتك.</p>',
                'meta_title_en' => 'About Us - EGK',
                'meta_title_ar' => 'من نحن - EGK',
                'meta_description_en' => 'Learn more about EGK and our mission.',
                'meta_description_ar' => 'تعرف على المزيد حول EGK ومهمتنا.',
                'is_active' => true,
            ]
        );

        Page::firstOrCreate(
            ['slug' => 'contact'],
            [
                'title_en' => 'Contact Us',
                'title_ar' => 'اتصل بنا',
                'content_en' => '<h2>Get in Touch</h2><p>We would love to hear from you. Please fill out the form below or contact us using the information provided.</p><h3>Contact Information</h3><p><strong>Email:</strong> info@egk.com</p><p><strong>Phone:</strong> +1 (555) 123-4567</p><p><strong>Address:</strong> 123 Main Street, City, Country</p>',
                'content_ar' => '<h2>تواصل معنا</h2><p>نود أن نسمع منك. يرجى ملء النموذج أدناه أو الاتصال بنا باستخدام المعلومات المقدمة.</p><h3>معلومات الاتصال</h3><p><strong>البريد الإلكتروني:</strong> info@egk.com</p><p><strong>الهاتف:</strong> +1 (555) 123-4567</p><p><strong>العنوان:</strong> 123 الشارع الرئيسي، المدينة، البلد</p>',
                'meta_title_en' => 'Contact Us - EGK',
                'meta_title_ar' => 'اتصل بنا - EGK',
                'meta_description_en' => 'Contact EGK for inquiries, support, or general information.',
                'meta_description_ar' => 'اتصل بـ EGK للاستفسارات أو الدعم أو المعلومات العامة.',
                'is_active' => true,
            ]
        );

        Page::firstOrCreate(
            ['slug' => 'privacy'],
            [
                'title_en' => 'Privacy Policy',
                'title_ar' => 'سياسة الخصوصية',
                'content_en' => '<h2>Privacy Policy</h2><p>Last updated: ' . date('F d, Y') . '</p><h3>Introduction</h3><p>At EGK, we take your privacy seriously. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our application.</p><h3>Information We Collect</h3><p>We may collect information about you in a variety of ways. The information we may collect includes:</p><ul><li>Personal identification information (name, email address, etc.)</li><li>Usage data and analytics</li><li>Device information</li></ul><h3>How We Use Your Information</h3><p>We use the information we collect to:</p><ul><li>Provide and maintain our service</li><li>Notify you about changes to our service</li><li>Provide customer support</li><li>Gather analysis or valuable information</li></ul><h3>Data Security</h3><p>We implement appropriate technical and organizational security measures to protect your personal information.</p><h3>Contact Us</h3><p>If you have any questions about this Privacy Policy, please contact us.</p>',
                'content_ar' => '<h2>سياسة الخصوصية</h2><p>آخر تحديث: ' . date('F d, Y') . '</p><h3>مقدمة</h3><p>في EGK، نأخذ خصوصيتك على محمل الجد. توضح سياسة الخصوصية هذه كيفية جمع معلوماتك واستخدامها والكشف عنها وحمايتها عند استخدام تطبيقنا.</p><h3>المعلومات التي نجمعها</h3><p>قد نجمع معلومات عنك بطرق متنوعة. قد تشمل المعلومات التي نجمعها:</p><ul><li>معلومات التعريف الشخصية (الاسم، عنوان البريد الإلكتروني، إلخ)</li><li>بيانات الاستخدام والتحليلات</li><li>معلومات الجهاز</li></ul><h3>كيف نستخدم معلوماتك</h3><p>نستخدم المعلومات التي نجمعها لـ:</p><ul><li>توفير وصيانة خدمتنا</li><li>إعلامك بالتغييرات على خدمتنا</li><li>توفير دعم العملاء</li><li>جمع التحليل أو المعلومات القيمة</li></ul><h3>أمان البيانات</h3><p>ننفذ تدابير أمنية فنية وتنظيمية مناسبة لحماية معلوماتك الشخصية.</p><h3>اتصل بنا</h3><p>إذا كان لديك أي أسئلة حول سياسة الخصوصية هذه، يرجى الاتصال بنا.</p>',
                'meta_title_en' => 'Privacy Policy - EGK',
                'meta_title_ar' => 'سياسة الخصوصية - EGK',
                'meta_description_en' => 'Read our Privacy Policy to understand how we collect and use your information.',
                'meta_description_ar' => 'اقرأ سياسة الخصوصية الخاصة بنا لفهم كيفية جمع معلوماتك واستخدامها.',
                'is_active' => true,
            ]
        );

        // Create default FAQs
        $faqs = [
            [
                'question_en' => 'What is EGK?',
                'question_ar' => 'ما هو EGK؟',
                'answer_en' => '<p>EGK is a modern application designed to meet your business needs. We provide comprehensive solutions for managing your content, settings, and user interactions.</p>',
                'answer_ar' => '<p>EGK هو تطبيق حديث مصمم لتلبية احتياجات عملك. نقدم حلولاً شاملة لإدارة المحتوى والإعدادات وتفاعلات المستخدمين.</p>',
                'order' => 1,
            ],
            [
                'question_en' => 'How do I contact support?',
                'question_ar' => 'كيف يمكنني الاتصال بالدعم؟',
                'answer_en' => '<p>You can contact our support team through the contact page or by emailing us at info@egk.com. We typically respond within 24 hours.</p>',
                'answer_ar' => '<p>يمكنك الاتصال بفريق الدعم من خلال صفحة الاتصال أو عن طريق إرسال بريد إلكتروني إلينا على info@egk.com. نرد عادة خلال 24 ساعة.</p>',
                'order' => 2,
            ],
            [
                'question_en' => 'Is my data secure?',
                'question_ar' => 'هل بياناتي آمنة؟',
                'answer_en' => '<p>Yes, we take data security very seriously. All your information is encrypted and stored securely. Please refer to our Privacy Policy for more details.</p>',
                'answer_ar' => '<p>نعم، نحن نأخذ أمان البيانات على محمل الجد. جميع معلوماتك مشفرة ومخزنة بشكل آمن. يرجى الرجوع إلى سياسة الخصوصية الخاصة بنا لمزيد من التفاصيل.</p>',
                'order' => 3,
            ],
            [
                'question_en' => 'Can I customize the application?',
                'question_ar' => 'هل يمكنني تخصيص التطبيق؟',
                'answer_en' => '<p>Yes, you can customize various aspects of the application through the admin panel, including settings, pages, and content management.</p>',
                'answer_ar' => '<p>نعم، يمكنك تخصيص جوانب مختلفة من التطبيق من خلال لوحة التحكم، بما في ذلك الإعدادات والصفحات وإدارة المحتوى.</p>',
                'order' => 4,
            ],
            [
                'question_en' => 'Do you support multiple languages?',
                'question_ar' => 'هل تدعمون لغات متعددة؟',
                'answer_en' => '<p>Yes, our application supports both English and Arabic languages. You can enable or disable Arabic language support in the settings.</p>',
                'answer_ar' => '<p>نعم، تطبيقنا يدعم اللغتين الإنجليزية والعربية. يمكنك تفعيل أو تعطيل دعم اللغة العربية في الإعدادات.</p>',
                'order' => 5,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::firstOrCreate(
                ['question_en' => $faq['question_en']],
                array_merge($faq, ['is_active' => true])
            );
        }

        // Seed Lebanese cities
        $this->call(CitySeeder::class);
        
        // Seed package types
        $this->call(PackageTypeSeeder::class);
        
        // Seed senders
        $this->call(SenderSeeder::class);
        
        // Seed packages
        $this->call(PackageSeeder::class);

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin user: admin@example.com / password');
        $this->command->info('Sender Manager user: sender-manager@example.com / password');
        $this->command->info('Pages created: About, Contact, Privacy');
        $this->command->info('FAQs created: ' . count($faqs) . ' questions');
        $this->command->info('Cities: Lebanese cities seeded');
        $this->command->info('Senders: Sample senders seeded');
        $this->command->info('Roles: Admin (all permissions), Sender Manager (view & update senders), User (view senders)');
    }
}
