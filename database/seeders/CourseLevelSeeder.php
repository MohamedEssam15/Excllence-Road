<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseLevelSeeder extends Seeder
{
    public function run()
    {
        $levels = [
            ['name' => 'beginner'],
            ['name' => 'intermediate'],
            ['name' => 'expert'],
        ];

        foreach ($levels as $level) {
            $levelModel = \App\Models\CourseLevel::create($level);
            $translations = [
                ['locale' => 'en', 'name' => ucfirst($level['name'])],
                ['locale' => 'ar', 'name' => $this->translateToArabic($level['name'])],
            ];
            $levelModel->translations()->createMany($translations);
        }
    }

    private function translateToArabic($key)
    {
        return match ($key) {
            'beginner' => 'مبتدئ',
            'intermediate' => 'متوسط',
            'expert' => 'خبير',
        };
    }
}
