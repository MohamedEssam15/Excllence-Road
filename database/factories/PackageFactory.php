<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Package>
 */
class PackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        
        return [
            "name"=>fake('en')->name(),
            "description"=>fake('en')->paragraph(),
            "price"=>fake('en')->randomFloat(2, 5, 1300),
            "start_date"=>fake('en')->dateTimeBetween('now', '+1 month'),
            "end_date"=>fake('en')->dateTimeBetween('+2 month', '+4 months'),
        ];
    }
}
