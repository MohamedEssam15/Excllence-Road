<?php

namespace Database\Factories;

use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class LessonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $unitIdsArray = Unit::pluck('id');
        return [
            'name' => fake('en')->word(),
            'description' => fake('en')->word(),
            'type' => fake('en')->randomElement(['video','meeting']),
            'video_link' => fake('en')->url(),
            'unit_id' => $unitIdsArray->random(),
        ];
    }
}
