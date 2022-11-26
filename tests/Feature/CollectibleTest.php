<?php

use App\Models\Award;
use App\Models\Collectible;
use App\Models\Meal;
use App\Models\Registration;
use App\Models\User;

it('can award a collectible to a user', function () {
    $user = User::factory()->create();
    $collectible = Collectible::factory()->create();

    $collectible->awardTo($user);

    expect($user->awards)
        ->toHaveLength(1)
        ->first()->collectible_id->toBe($collectible->id)
        ->first()->awarded->toBe(1);
    expect($user->collectibles)
        ->toHaveLength(1)
        ->first()->id->toBe($collectible->id);
});

it('can award the same collectible multiple times', function () {
    $user = User::factory()->create();
    $collectible = Collectible::factory()->create();

    $collectible->awardTo($user);

    expect($user->awards)
        ->toHaveLength(1)
        ->first()->awarded->toBe(1);

    $collectible->awardTo($user);

    expect($user->fresh()->awards)
        ->toHaveLength(1)
        ->first()->awarded->toBe(2);
});

test('a meal awards a collectible', function () {
    $user = User::factory()->create();
    $collectible = Collectible::factory()->create();
    $meal = Meal::factory()->available()->create(['collectible_id' => $collectible->id]);

    $this->actingAs($user)
        ->postJson(route('meal.register'), [
            'meal_id' => $meal->id,
        ])
        ->assertNoContent();

    expect($user->collectibles)->toHaveCount(1);
    expect($user->collectibles->contains($collectible))->toBeTrue();
});

test('anonymous registrations don\'t get collectibles', function () {
    $meal = Meal::factory()->available()->create();

    $this->postJson(route('meal.register'), [
        'meal_id' => $meal->id,
        'name' => 'Hans Talmon',
        'email' => 'hans@example.com',
        'handicap' => null,
    ])
        ->assertNoContent();

    expect(Award::all())->toHaveCount(0);
});

it('assigns a random collectible to a meal by default', function () {
    $collectible = Collectible::factory()->create();
    $meal = Meal::factory()->create();
    $meal->save();

    expect($meal->fresh()->awardsCollectible->is($collectible))->toBeTrue();
});

it('does not assign a random collectible to a meal by default when none are available', function () {
    $meal = Meal::factory()->create();
    $meal->save();

    expect($meal->fresh()->awardsCollectible)->toBeNull();
});

it('does not assign a random collectible to a meal when updating', function () {
    $meal = Meal::factory()->withCollectible()->create();
    $originalCollectible = $meal->awardsCollectible;
    Collectible::factory()->count(100)->create(); // accept a 1% risk we select the same by accident
    $meal->save();

    expect($meal->fresh()->awardsCollectible)->toEqual($originalCollectible);
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

test('re-registering awards the collectible again', function () {
    $user = User::factory()->create();
    $collectible = Collectible::factory()->create();
    $meal = Meal::factory()->available()->create(['collectible_id' => $collectible->id]);
    Registration::factory()->create([
        'meal_id' => $meal->id,
        'user_id' => $user->id,
    ]);
    $collectible->awardTo($user);
    $collectible->awardTo($user);

    $user = $user->fresh();
    expect($user->collectibles->contains($collectible))->toBeTrue();
    expect($user->awards()->first()->awarded)->toBe(2);

    $this->actingAs($user)
        ->postJson(route('meal.deregister'), [
            'meal_id' => $meal->id,
        ])
        ->assertNoContent();

    $user = $user->fresh();
    expect($user->collectibles->contains($collectible))->toBeTrue();
    expect($user->awards()->first()->awarded)->toBe(1);

    $this->actingAs($user)
        ->postJson(route('meal.register'), [
            'meal_id' => $meal->id,
        ])
        ->assertNoContent();

    $user = $user->fresh();
    expect($user->collectibles->contains($collectible))->toBeTrue();
    expect($user->awards()->first()->awarded)->toBe(2);
});

// a page to view your collectibles
// unawarded collectibles are greyed out
// popup for confirmation shows new collectible
// GIFs are NOT linked to a specific meal, two users registering can get different GIFs (could drop?)
