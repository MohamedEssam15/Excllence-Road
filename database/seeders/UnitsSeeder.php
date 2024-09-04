<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arUnits = [
            'الجبر',            // Algebra
            'الهندسة',          // Geometry
            'حساب التفاضل والتكامل', // Calculus
            'الفيزياء',         // Physics
            'الكيمياء',         // Chemistry
            'البيولوجيا',       // Biology
            'النحو',            // Grammar
            'الأدب',            // Literature
            'اللغات الأجنبية',  // Foreign Languages
            'التاريخ العالمي',  // World History
            'الجغرافيا البشرية', // Human Geography
            'التربية البدنية',  // Physical Education
            'برمجة الويب',       // Web Programming
            'تصميم الجرافيك',   // Graphic Design
            'الرواية',          // Novel
            'علم النفس',        // Psychology
            'الاقتصاد الجزئي',  // Microeconomics
            'الفلسفة الغربية',  // Western Philosophy
            'التربية الفنية',   // Art Education
            'الحماية البيئية',  // Environmental Protection
            'التغذية الصحية',   // Healthy Nutrition
            'الفقه الإسلامي',   // Islamic Jurisprudence
        ];
       $units =  \App\Models\Unit::factory()->count(10)->create();

        foreach ($units as $unit) {
            $translations = [
                ['locale' => 'en', 'name' => ucfirst($unit->name)],
                ['locale' => 'ar', 'name' => fake('ar_001')->randomElement($arUnits)],
            ];
            $unit->translations()->createMany($translations);
        }
    }
}
