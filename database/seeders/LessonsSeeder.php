<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LessonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arLessonsName=[
            'مقدمة في الجبر',          // Introduction to Algebra
            'قوانين نيوتن للحركة',     // Newton's Laws of Motion
            'التركيب الكيميائي',       // Chemical Bonding
            'الخلية وعلم الأحياء',     // Cell Biology
            'قواعد اللغة الإنجليزية',  // English Grammar Rules
            'الثورة الفرنسية',        // The French Revolution
            'أنظمة الطقس',           // Weather Systems
            'مبادئ البرمجة',          // Programming Principles
            'أساسيات التصميم',        // Design Fundamentals
            'الأدب العربي القديم',     // Ancient Arabic Literature
            'الأنظمة الاجتماعية',     // Social Systems
            'المالية الدولية',        // International Finance
            'المدارس الفلسفية',        // Philosophical Schools
            'التعبير الفني',          // Artistic Expression
            'تغير المناخ',            // Climate Change
            'الوقاية من الأمراض',     // Disease Prevention
            'فروع الفقه الإسلامي'      // Islamic Jurisprudence Branches
        ];
        $arLessonsDescription=[
            'درس يقدم مقدمة حول أساسيات الجبر، بما في ذلك المعادلات والمصفوفات.',
            'درس يشرح قوانين نيوتن الأساسية في الحركة وكيفية تطبيقها.',
            'محتوى تعليمي حول الروابط الكيميائية وأنواعها المختلفة.',
            'دروس في علم الأحياء، مع التركيز على تركيب الخلية ووظائفها.',
            'دورة في قواعد اللغة الإنجليزية، بما في ذلك النحو والصرف.',
            'درس عن الثورة الفرنسية وتأثيرها على التاريخ الأوروبي والعالمي.',
            'محتوى تعليمي حول أنظمة الطقس وكيفية تنبؤ الأحوال الجوية.',
            'مقدمة في مبادئ البرمجة الأساسية وكيفية كتابة الأكواد.',
            'درس في أساسيات التصميم، بما في ذلك الألوان والأشكال والطباعة.',
            'محتوى تعليمي حول الأدب العربي القديم، بما في ذلك الشعر والنثر.',
            'دروس في الأنظمة الاجتماعية وكيفية تأثيرها على الأفراد والمجتمعات.',
            'درس حول المالية الدولية والتجارة وكيفية تأثيرها على الاقتصاد العالمي.',
            'محتوى تعليمي في المدارس الفلسفية المختلفة وأفكارها الرئيسية.',
            'درس يركز على التعبير الفني من خلال الفنون التشكيلية والرسم.',
            'محتوى تعليمي حول تغير المناخ وتأثيراته على البيئة.',
            'دروس في الوقاية من الأمراض وأساليب تعزيز الصحة.',
            'محتوى تعليمي في فروع الفقه الإسلامي وتفسير القوانين الشرعية.'
        ];

        $lessons =  \App\Models\Lesson::factory()->count(10)->create();

        foreach ($lessons as $lesson) {
            $translations = [
                ['locale' => 'en', 'name' => ucfirst($lesson->name), 'description' => $lesson->description],
                ['locale' => 'ar', 'name' => fake('ar_001')->randomElement($arLessonsName), 'description' => fake('ar_001')->randomElement($arLessonsDescription)],
            ];
            $lesson->translations()->createMany($translations);
        }
    }
}
