<?php

namespace Database\Factories;

use App\Models\Meal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Registration>
 */
class RegistrationFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'handicap' => $this->faker->sentence(),
            'meal_id' => Meal::factory(),
            'email' => $this->faker->email(),
            'salt' => $this->faker->numberBetween(100_000, 999_999),
            'username' => $this->faker->username(),
            'confirmed' => $this->faker->boolean(),
            'user_id' => User::factory(),
            'created_by' => User::factory(),
        ];
    }
}
