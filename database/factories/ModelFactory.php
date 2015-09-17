<?php

use App\Models\Meal;
use App\Models\Registration;
use App\Models\User;
use Faker\Generator;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/
$factory->define(Meal::class, function (Generator $faker) {
    return [
        'meal_timestamp'   => $faker->dateTimeBetween('-10 days', '+30 days'),
        'locked_timestamp' => $faker->dateTimeBetween('-10 days', '+30 days'),
        'event'            => rand(1, 4) == 4 ? $faker->sentence(rand(1,4)) : null
    ];
});

$factory->define(Registration::class, function (Generator $faker) use ($factory) {
    return [
        'name'      => $faker->name(),
        'handicap'  => $faker->sentence(rand(1,3)),
        'meal_id'   => $factory->raw(Meal::class),
        'email'     => $faker->email(),
        'confirmed' => true
    ];
});

$factory->define(User::class, function (Generator $faker) {
    return [
        'username' => $faker->username(),
        'handicap' => $faker->sentence(rand(1,3)),
        'name'     => $faker->name(),
        'blocked'  => false,
        'email'    => $faker->email()
    ];
});

$factory->defineAs(Registration::class, 'userRegistration', function(Generator $faker) use ($factory) {
    $registration = $factory->raw(Registration::class);
    $user = $factory->create(User::class);

    return array_merge($registration, [
        'user_id'  => $user->id,
        'name'     => $user->name,
        'handicap' => $user->handicap,
        'email'    => $user->email,
        'username' => $user->username,
    ]);
});
