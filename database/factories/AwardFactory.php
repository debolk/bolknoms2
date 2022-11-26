<?php

namespace Database\Factories;

use App\Models\Collectible;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Award>
 */
class AwardFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'collectible_id' => Collectible::factory(),
            'awarded' => $this->faker->numberBetween(0, 52),
        ];
    }
}
