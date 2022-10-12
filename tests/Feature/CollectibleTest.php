<?php

use App\Models\Collectible;
use App\Models\User;

it('can award a collectible to a user', function () {
    $user = User::factory()->create();
    $collectible = Collectible::factory()->create();

    $collectible->awardTo($user);

    expect($user->collectibles)
        ->toHaveLength(1)
        ->first()->id->toBe($collectible->id);
});
