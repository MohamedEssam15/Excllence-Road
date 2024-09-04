<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CoursesStatuesSeeder extends Seeder
{
    public function run()
    {
        $statuses = [
            ['name' => 'active'], //working
            ['name' => 'pending'], //waiting confirmation from admin
            ['name' => 'cancelled'], //rejected from admin
            ['name' => 'paused'], //expired or paused from admin
        ];

        foreach ($statuses as $status) {
            $statusModel = \App\Models\CourseStatus::create($status);
            $translations = [
                ['locale' => 'en', 'name' => ucfirst($status['name'])],
                ['locale' => 'ar', 'name' => $this->translateToArabic($status['name'])],
            ];
            $statusModel->translations()->createMany($translations);
        }
    }

    private function translateToArabic($key)
    {
        return match ($key) {
            'active' => 'مفعل',
            'pending' => 'قيد الانتظار',
            'cancelled' => 'تم الغاء',
            'paused' => 'متوقف مؤقتا',
        };
    }
}
