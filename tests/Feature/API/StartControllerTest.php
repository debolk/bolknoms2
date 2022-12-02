<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('has an API starting point for HATEOAS', function () {
    Sanctum::actingAs(User::factory()->create());

    $this->get(route('api.start'))
        ->assertOk()
        ->assertJsonPath('links.0.rel', 'meals.upcoming');
});
