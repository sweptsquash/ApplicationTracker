<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Application>
 */
class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::factory()->create()->id,
            'title' => fake()->jobTitle,
            'url' => fake()->url,
            'salary_period' => fake()->randomElement(['hourly', 'daily', 'weekly', 'monthly', 'yearly']),
            'salary_type' => fake()->randomElement(['range', 'fixed', 'unknown']),
            'salary_min' => fake()->numberBetween(10000, 100000),
            'salary_max' => fake()->numberBetween(100000, 1000000),
            'status' => fake()->randomElement(['applied', 'awaiting_response', 'interviewing', 'offer', 'rejected', 'offer_accepted', 'withdrawn']),
            'notes' => fake()->paragraph(),
            'applied_at' => fake()->dateTimeThisYear(),
        ];
    }
}
