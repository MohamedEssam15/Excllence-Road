<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Unit>
 */
class UnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $coursesIdsArray = Course::pluck('id');
        return [
            'name'=>fake('en')->word(),
            'course_id'=>$coursesIdsArray->random(),
        ];
    }
}
