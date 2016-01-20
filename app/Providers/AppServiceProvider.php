<?php

namespace App\Providers;

use App\Http\Helpers\Navigation;
use App\Http\Helpers\OAuth;
use App\Http\Helpers\ProfilePicture;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Force correct locale on every boot
        setlocale(LC_TIME, "nl_NL.utf8");
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Identify OAuth class as a singleton in the repository
        $this->app->singleton(OAuth::class, function() {
            return new OAuth($this->app[Request::class]);
        });

        $this->app->singleton(Navigation::class, function() {
            return new Navigation($this->app[OAuth::class]);
        });

        $this->app->bind(profilePicture::class, function() {
            return new ProfilePicture($this->app[OAuth::class]);
        });
    }

}
