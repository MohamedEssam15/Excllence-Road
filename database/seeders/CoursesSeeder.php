<?php

namespace Database\Seeders;

use App\Models\category;
use App\Models\CourseStatus;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arCourseNames = [
            'الرياضيات الأساسية',   // Basic Mathematics
            'الفيزياء العامة',      // General Physics
            'الكيمياء العضوية',    // Organic Chemistry
            'علم الأحياء',         // Biology
            'اللغة الإنجليزية',    // English Language
            'التاريخ الحديث',      // Modern History
            'الجغرافيا الطبيعية',  // Physical Geography
            'البرمجة بلغة PHP',    // PHP Programming
            'تصميم الجرافيك',     // Graphic Design
            'الأدب العربي',        // Arabic Literature
            'علم الاجتماع',       // Sociology
            'الاقتصاد الدولي',     // International Economics
            'الفلسفة الشرقية',     // Eastern Philosophy
            'التربية الفنية',      // Art Education
            'البيئة والاستدامة',   // Environment and Sustainability
            'الصحة العامة',        // Public Health
            'التربية الإسلامية'    // Islamic Education
        ];
        $arCourseDescriptions = [
            'دورة تغطي أساسيات الرياضيات بما في ذلك الجبر والهندسة.',
            'دروس في مفاهيم الفيزياء الأساسية، مثل الحركة والطاقة.',
            'محتوى تعليمي عن الكيمياء العضوية والتركيبات الكيميائية.',
            'دروس في علم الأحياء بما في ذلك التركيب الخلوي والتطور.',
            'تعليم قواعد ومهارات اللغة الإنجليزية وتطوير القدرات اللغوية.',
            'محتوى تعليمي عن الأحداث التاريخية الرئيسية في العصر الحديث.',
            'دروس في الجغرافيا الطبيعية، بما في ذلك التضاريس والمناخ.',
            'تعليم البرمجة بلغة PHP وتطوير تطبيقات الويب.',
            'دروس في تصميم الجرافيك بما في ذلك البرامج والتقنيات الحديثة.',
            'محتوى تعليمي في الأدب العربي من خلال النصوص والشعر.',
            'دروس في علم الاجتماع والأنظمة الاجتماعية والظواهر الثقافية.',
            'محتوى تعليمي في الاقتصاد الدولي والتجارة العالمية.',
            'دروس في الفلسفة الشرقية والنظريات الفلسفية في آسيا.',
            'تعليم الفنون والتعبير الفني من خلال الرسم والنحت والتصميم.',
            'محتوى تعليمي حول قضايا البيئة والاستدامة وحماية الموارد.',
            'دروس في الصحة العامة والتغذية وكيفية تعزيز الرفاهية.',
            'محتوى تعليمي في الدين الإسلامي، الفقه، وتاريخ الإسلام.'
        ];
        $arClassNames = [
            'الصف الأول',          // Grade 1
            'الصف الثاني',         // Grade 2
            'الصف الثالث',         // Grade 3
            'الصف الرابع',         // Grade 4
            'الصف الخامس',         // Grade 5
            'الصف السادس',         // Grade 6
            'الصف السابع',         // Grade 7
            'الصف الثامن',         // Grade 8
            'الصف التاسع',         // Grade 9
            'الصف العاشر',         // Grade 10
            'الصف الحادي عشر',     // Grade 11
            'الصف الثاني عشر',     // Grade 12
            'الدورة الجامعية الأولى', // Undergraduate Year 1
            'الدورة الجامعية الثانية', // Undergraduate Year 2
            'الدورة الجامعية الثالثة', // Undergraduate Year 3
            'الدورة الجامعية الرابعة', // Undergraduate Year 4
            'الدراسات العليا',     // Graduate Studies
            'الدكتوراه',          // Doctoral Studies
        ];
       $courses =  \App\Models\Course::factory()->count(25)->create();
        foreach ($courses as $course) {
            $dir=public_path('course_attachments/'.$course->id.'/cover_photo');
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }

            $imagePath = fake('en')->image($dir, 640, 480, 'education', false);
            $course->cover_photo_name = $imagePath;
            $course->save();
            $translations = [
                ['locale' => 'en', 'name' => ucfirst($course->name),'description'=>$course->description,'specific_to'=>$course->specific_to],
                ['locale' => 'ar', 'name' => fake('ar_001')->randomElement($arCourseNames),'description'=>fake('ar_001')->randomElement($arCourseDescriptions),'specific_to'=>fake('ar_001')->randomElement($arClassNames)],
            ];
            $course->translations()->createMany($translations);
        }
    }
}
