<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'=>Fake()->name,
            'user_id'=>rand(1,3),
            'category_id'=>rand(1,9),
            'job_type_id'=>rand(1,9),
            'Vacancy'=>rand(1,5),
            'salary'=>rand(1,12),
            'location'=>Fake()->city,
            'description'=>Fake()->text,
            'experience'=>rand(1,10),
            'company_name'=>Fake()->name

        ];
    }
}
