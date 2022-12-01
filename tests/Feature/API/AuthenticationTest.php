<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

test('users can reset their token', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('auth.token.create'))
        ->assertOk()
        ->assertJson(
            fn (AssertableJson $json) => $json->has('token')
        );

    expect($user->tokens)->toHaveCount(1);
    expect($response['token'])->not()->toBeEmpty();
});

it('invalidates existing tokens when resetting', function () {
    $user = User::factory()->create();
    $user->createToken('foo');
    $user->createToken('bar');

    $response = $this->actingAs($user)
        ->post(route('auth.token.create'))
        ->assertOk()
        ->assertJson(
            fn (AssertableJson $json) => $json->has('token')
        );

    expect($user->tokens)->toHaveCount(1);
    expect($user->tokens->first()->name)->toBe('default');
});
