<?php

namespace App\Providers;

use App\BolkLogin\BolkLoginSocialiteProvider;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory;

class BolkLoginServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $socialite = app(Factory::class);
        $socialite->extend('BolkLogin', function () use ($socialite) { // @phpstan-ignore-line
            // @phpstan-ignore-next-line
            return $socialite->buildProvider(BolkLoginSocialiteProvider::class, config('services.bolklogin'));
        });
    }
}
