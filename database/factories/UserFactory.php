<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => fake()->unique()->uuid(),
            'role_id' => Role::firstWhere('name', 'user')->id,
            'username' => fake()->unique()->userName(),
            'display_name' => fake()->userName(),
            'password' => Hash::make('password'),
            'country_id' => Country::findCode('GB')->id,
            'email' => fake()->unique()->email(),
        ];
    }
}
