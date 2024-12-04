<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            "comment" => fake('en')->text(150),
            'course_id' => Course::all()->random()->id,
            'student_id' => User::all()->random()->id,
            'rating' => fake('ar')->randomFloat(2, 1, 5),
        ];
    }
}
