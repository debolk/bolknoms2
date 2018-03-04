<?php

namespace App\Providers;

use App\Http\Helpers\Navigation;
use App\Http\Helpers\OAuth;
use App\Http\Helpers\ProfilePicture;
use Illuminate\Support\ServiceProvider;
use Session;

class AppServiceProvider extends ServiceProvider
{

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
        $this->app->alias('bugsnag.logger', \Illuminate\Contracts\Logging\Log::class);
        $this->app->alias('bugsnag.logger', \Psr\Log\LoggerInterface::class);

        // Identify OAuth class as a singleton in the repository
        $this->app->singleton(OAuth::class, function () {
            return new OAuth($this->app[Session::class]);
        });

        $this->app->singleton(Navigation::class, function () {
            return new Navigation($this->app[OAuth::class]);
        });

        $this->app->bind(ProfilePicture::class, function () {
            return new ProfilePicture($this->app[OAuth::class]);
        });
    }
}
