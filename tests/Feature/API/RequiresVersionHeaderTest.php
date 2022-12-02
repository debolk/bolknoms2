<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('allows requests with a valid version', function () {
    Sanctum::actingAs(User::factory()->create());

    $this->withHeader('Accept', 'application/vnd.bolknoms.v1+json')
        ->get(route('api.meals.upcoming'))
        ->assertStatus(200);
});

it('requires a version header', function () {
    Sanctum::actingAs(User::factory()->create());

    $this->withHeader('Accept', '')
        ->get(route('api.meals.upcoming'))
        ->assertStatus(406)
        ->assertJsonPath('errors.0.code', 'accepts_header_missing');
});

it('requires a valid version identifier', function () {
    Sanctum::actingAs(User::factory()->create());

    $this->withHeader('Accept', 'application/vnd.bolknoms.v2+json')
        ->get(route('api.meals.upcoming'))
        ->assertStatus(406)
        ->assertJsonPath('errors.0.code', 'accepts_header_unsupported');
});
