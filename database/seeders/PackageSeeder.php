<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packageNames = [
            'حزمة الرياضيات المتكاملة',          // Comprehensive Mathematics Package
            'حزمة العلوم الأساسية',             // Basic Science Package
            'حزمة تعلم البرمجة',                 // Programming Learning Package
            'حزمة اللغات الأجنبية',              // Foreign Language Package
            'حزمة الفنون والإبداع',               // Arts and Creativity Package
            'حزمة الأدب والثقافة العربية',        // Arabic Literature and Culture Package
            'حزمة التاريخ والجغرافيا',            // History and Geography Package
            'حزمة الصحة واللياقة',                // Health and Fitness Package
            'حزمة إدارة الأعمال والاقتصاد',       // Business Management and Economics Package
            'حزمة الفلسفة والدراسات الاجتماعية',  // Philosophy and Social Studies Package
            'حزمة البيئة والاستدامة',             // Environment and Sustainability Package
            'حزمة الدراسات الإسلامية',            // Islamic Studies Package
        ];

        $packageDescriptions = [
            'تشمل هذه الحزمة دورات في الجبر والهندسة وحساب التفاضل والتكامل لمساعدة الطلاب على إتقان مهارات الرياضيات.',
            'حزمة تغطي الدورات الأساسية في الفيزياء والكيمياء والبيولوجيا لتوفير فهم شامل لمجالات العلوم المختلفة.',
            'مجموعة من الدورات التي تعلم البرمجة بلغات متعددة مثل PHP وPython وJavaScript.',
            'حزمة مخصصة لتعلم لغات أجنبية مثل الإنجليزية، الفرنسية، الإسبانية، والألمانية لتعزيز المهارات اللغوية.',
            'تشمل هذه الحزمة دورات في التصميم الجرافيكي والفنون الجميلة والموسيقى لتطوير المهارات الإبداعية.',
            'تغطي حزمة الأدب والثقافة العربية الدروس في الشعر العربي والنصوص القديمة والمسرح.',
            'حزمة تحتوي على دورات في التاريخ الحديث والقديم والجغرافيا الطبيعية والبشرية.',
            'تتضمن هذه الحزمة دورات في التغذية والصحة العامة واللياقة البدنية لتعزيز الوعي الصحي.',
            'مجموعة من الدورات التي تركز على إدارة الأعمال والاقتصاد، بما في ذلك التسويق والتمويل والمحاسبة.',
            'تشمل حزمة من الدورات التي تتناول مواضيع الفلسفة الكلاسيكية والحديثة والدراسات الاجتماعية.',
            'حزمة مخصصة لتعليم قضايا البيئة مثل تغير المناخ والاستدامة وإدارة الموارد.',
            'مجموعة من الدورات في الدراسات الإسلامية بما في ذلك الفقه والعقيدة والسيرة النبوية.'
        ];
        $coursesIdsArray = Course::pluck('id')->toArray();
        $packages =  \App\Models\Package::factory()->count(10)->create();
        foreach ($packages as $package) {
            $dir = public_path('packages_attachments/' . $package->id . '/cover_photo');
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }

            $imagePath = fake('en')->image($dir, 640, 480, 'education', false);
            $package->cover_photo = $imagePath;
            $package->save();
            $translations = [
                ['locale' => 'en', 'name' => ucfirst($package->name), 'description' => $package->description],
                ['locale' => 'ar', 'name' => fake('ar_001')->randomElement($packageNames), 'description' => fake('ar_001')->randomElement($packageDescriptions)],
            ];
            $randomIds = array_map(fn($key) => $coursesIdsArray[$key], array_rand($coursesIdsArray, 5));
            $package->courses()->sync($randomIds);
            $package->translations()->createMany($translations);
        }
    }
}
