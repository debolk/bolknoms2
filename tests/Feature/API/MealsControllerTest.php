<?php

use App\Models\Meal;
use App\Models\User;
use Carbon\Carbon;
use Laravel\Sanctum\Sanctum;

it('shows upcoming meals', function () {
    Carbon::setTestNow('2022-03-09 19:00:00');

    Meal::factory()->create(['meal_timestamp' => '2022-03-08 18:30:00']);
    Meal::factory()->create([
        'meal_timestamp' => '2022-03-09 18:30:00',
        'uuid' => '74696411-a679-4eaf-8273-039d92864873',
        'capacity' => 13,
        'event' => 'celebration dinner',
    ]);
    Meal::factory()->create([
        'meal_timestamp' => '2022-03-10 18:30:00',
        'uuid' => 'b73688a4-a645-449f-893c-a754bd77132f',
    ]);

    Sanctum::actingAs(User::factory()->create());

    $response = $this->get(route('api.meals.upcoming'))
        ->assertOk();

    expect($response['data'])->toHaveCount(2);
    expect($response['data'][0]['meal_time'])->toBe('2022-03-09T18:30:00+01:00');
    expect($response['data'][0]['id'])->toBe('74696411-a679-4eaf-8273-039d92864873');
    expect($response['data'][0]['capacity'])->toBe(13);
    expect($response['data'][0]['event'])->toBe('celebration dinner');
    expect($response['data'][1]['meal_time'])->toBe('2022-03-10T18:30:00+01:00');
    expect($response['data'][1]['id'])->toBe('b73688a4-a645-449f-893c-a754bd77132f');
});
