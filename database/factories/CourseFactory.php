<?php

namespace Database\Factories;

use App\Models\category;
use App\Models\CourseLevel;
use App\Models\CourseStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userIdsArray = User::role('teacher')->pluck('id');
        $categoriesIdsArray = category::pluck('id');
        $courseStatusIdsArray = CourseStatus::pluck('id');
        $courseLevelsIdsArray = CourseLevel::pluck('id');
        // $imagePath = fake('en')->image(public_path('cover_photos'), 640, 480, 'education', false);
        return [
            "name"=>fake('en')->name(),
            "description"=>fake('en')->paragraph(),
            // "cover_photo_name"=>$imagePath,
            "teacher_commision"=>(string) fake('en')->randomFloat(2, 0, 100),
            "teacher_id"=>$userIdsArray->random(),
            "category_id"=>$categoriesIdsArray->random(),
            "level_id"=>$courseLevelsIdsArray->random(),
            "price"=>fake('en')->randomFloat(2, 5, 1300),
            "currency"=>fake('en')->randomElement(['SAR','USD']),
            "start_date"=>fake('en')->dateTimeBetween('now', '+1 month'),
            "end_date"=>fake('en')->dateTimeBetween('+2 month', '+4 months'),
            "is_populer"=>fake('en')->boolean(),
            "is_specific"=>fake('en')->boolean(),
            "specific_to"=>fake('en')->optional()->randomElement(['Primary', 'Secondary', 'Higher Secondary', 'Undergraduate']),
            "status_id"=>$courseStatusIdsArray->random(),
        ];
    }
}
