<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arCategories = [
            'الرياضيات',           // Mathematics
            'العلوم',               // Science
            'اللغة العربية',        // Arabic Language
            'اللغات الأجنبية',      // Foreign Languages
            'التاريخ',              // History
            'الجغرافيا',            // Geography
            'التربية البدنية',      // Physical Education
            'البرمجة',              // Programming
            'التصميم',              // Design
            'الأدب',                // Literature
            'العلوم الاجتماعية',    // Social Sciences
            'الاقتصاد',             // Economics
            'الفلسفة',              // Philosophy
            'التربية الفنية',       // Art Education
            'البيئة',               // Environment
            'الصحة',                // Health
            'التربية الإسلامية'     // Islamic Education
        ];
        $arDescriptions = [
            'دروس في مختلف فروع الرياضيات بما في ذلك الجبر والهندسة وحساب التفاضل والتكامل.',
            'محتوى تعليمي في علوم الفيزياء والكيمياء والبيولوجيا.',
            'دروس في قواعد اللغة العربية، الأدب، والنحو، والإملاء.',
            'تعليم لغات مختلفة مثل الإنجليزية، الفرنسية، الإسبانية، والألمانية.',
            'دروس تغطي الأحداث التاريخية المهمة وتطور الحضارات عبر الزمن.',
            'محتوى تعليمي عن المواقع الجغرافية، الخرائط، والبيئة.',
            'دروس في الرياضة والتمارين البدنية وأنشطة اللياقة البدنية.',
            'تعليم لغات البرمجة المختلفة مثل PHP، Python، JavaScript، وتطوير البرمجيات.',
            'دروس في تصميم الجرافيك، التصميم الرقمي، وأدوات التصميم المختلفة.',
            'دروس في الأدب العربي والعالمي، الشعر، والرواية.',
            'محتوى تعليمي في علم النفس، الاجتماع، والأنثروبولوجيا.',
            'دروس في مبادئ الاقتصاد، إدارة الأعمال، والمالية.',
            'محتوى تعليمي في الفلسفة والتفكير النقدي والنظريات الفلسفية.',
            'دروس في الفن، الرسم، والنحت، والتعبير الفني.',
            'محتوى تعليمي عن حماية البيئة، الاستدامة، وتغير المناخ.',
            'دروس في الصحة العامة، التوعية الصحية، والتغذية.',
            'محتوى تعليمي في الدين الإسلامي، الفقه، والتاريخ الإسلامي.'
        ];
       $categories =  \App\Models\Category::factory()->count(10)->create();

        foreach ($categories as $category) {
            $translations = [
                ['locale' => 'en', 'name' => ucfirst($category->name),'description'=>$category->description],
                ['locale' => 'ar', 'name' => fake('ar_001')->randomElement($arCategories),'description'=>fake('ar_001')->randomElement($arDescriptions)],
            ];
            $category->translations()->createMany($translations);
        }
    }
}
