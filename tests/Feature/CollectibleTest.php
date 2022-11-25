<?php

use App\Models\Collectible;
use App\Models\Meal;
use App\Models\Registration;
use App\Models\User;

it('can award a collectible to a user', function () {
    $user = User::factory()->create();
    $collectible = Collectible::factory()->create();

    $collectible->awardTo($user);

    expect($user->collectibles)
        ->toHaveLength(1)
        ->first()->id->toBe($collectible->id);
});

test('a registration awards a collectible', function () {
    $user = User::factory()->create();
    $collectible = Collectible::factory()->create();
    $meal = Meal::factory()->available()->create();

    $this->actingAs($user)
        ->postJson(route('meal.register'), [
            'meal_id' => $meal->id,
        ])
        ->assertNoContent();

    expect($user->collectibles->contains($collectible))->toBeTrue();
});

it('does not award a collectible when all are owned', function () {
    $user = User::factory()->create();
    $collectible = Collectible::factory()->count(3)->create();
    $collectible->each->awardTo($user);
    $meal = Meal::factory()->available()->create();

    $this->actingAs($user)
        ->postJson(route('meal.register'), [
            'meal_id' => $meal->id,
        ])
        ->assertNoContent();

    expect($user->collectibles)->toHaveCount(3);
});

test('deregistering removes the awarded collectible', function () {
    $user = User::factory()->create();
    $collectibleA = Collectible::factory()->create();
    $collectibleB = Collectible::factory()->create();
    $mealA = Meal::factory()->available()->create(['collectible_id' => $collectibleA->id]);
    $mealB = Meal::factory()->available()->create(['collectible_id' => $collectibleB->id]);
    Registration::factory()->create([
        'meal_id' => $mealA->id,
        'user_id' => $user->id,
    ]);
    Registration::factory()->create([
        'meal_id' => $mealB->id,
        'user_id' => $user->id,
    ]);
    $collectibleA->awardTo($user);
    $collectibleB->awardTo($user);

    expect($user->collectibles->contains($collectibleA))->toBeTrue();
    expect($user->collectibles->contains($collectibleB))->toBeTrue();

    $this->actingAs($user)
        ->postJson(route('meal.deregister'), [
            'meal_id' => $mealB->id,
        ])
        ->assertNoContent();

    $user = $user->fresh();
    expect($user->collectibles->contains($collectibleA))->toBeTrue();
    expect($user->collectibles->contains($collectibleB))->toBeFalse();
});

// can earn collectibles multiple times, with counter
// must assign collecible at random, allowing re-issues
// if a collectible is awarded twice, deregistering allows you to keep it
// anonymous users do not get collectibles
// a page to view your collectibles
// unawarded collectibles are greyed out
// popup for confirmation shows new collectible
// register > deregister > register must award the same GIF, or it never assigns a GIF as anti-cheat?
// GIFs are NOT linked to a specific meal, two users registering can get different GIFs (could drop?)
