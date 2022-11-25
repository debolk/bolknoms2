<?php

namespace Database\Factories;

use App\Models\Collectible;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meal>
 */
class MealFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'meal_timestamp' => $this->faker->dateTime(),
            'locked_timestamp' => $this->faker->dateTime(),
            'collectible_id' => Collectible::factory(),
        ];
    }

    public function available()
    {
        return $this->state(function (array $attributes) {
            return [
                'locked_timestamp' => $this->faker->dateTimeBetween(
                    Carbon::now()->addHour(),
                    Carbon::now()->addWeeks(100)
                ),
                'capacity' => null,
            ];
        });
    }
}
