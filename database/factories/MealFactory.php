<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meal>
 */
class MealFactory extends Factory
{
    public function definition()
    {
        return [
            'meal_timestamp' => $this->faker->dateTime(),
            'locked_timestamp' => $this->faker->dateTime(),
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
