<?php

use App\Models\Meal;
use App\Models\User;
use Carbon\Carbon;
use Laravel\Sanctum\Sanctum;

test('users can subscribe to meals', function () {
    Carbon::setTestNow('2022-03-09 19:00:00');
    $meal = Meal::factory()->create([
        'locked_timestamp' => '2022-03-10 15:00:00',
    ]);
    $user = User::factory()->create();

    Sanctum::actingAs($user);
    $this->post(route('api.meals.registrations', [$meal->uuid]))
        ->assertNoContent();

    expect($user->registrations)
        ->toHaveCount(1)
        ->first()->meal_id->toBe($meal->id);
});

it('returns JSON:API errors when failed', function () {
    Carbon::setTestNow('2022-03-09 19:00:00');
    $meal = Meal::factory()->create([
        'locked_timestamp' => '2022-03-09 15:00:00',
    ]);
    $user = User::factory()->create();

    Sanctum::actingAs($user);
    $this->post(route('api.meals.registrations', [$meal->uuid]))
        ->assertStatus(400)
        ->assertJsonPath('errors.0.code', 'meal_deadline_expired');

    expect($user->registrations)
        ->toHaveCount(0);
});
