<?php

namespace App\Http\Middleware;

use App\Http\Helpers\OAuth;
use Closure;
use Illuminate\Support\Facades\View;

class LoggedInUserData
{
    private $oauth;

    public function __construct(OAuth $oauth)
    {
        $this->oauth = $oauth;
    }

    public function handle($request, Closure $next)
    {
        // Make the current user data (or null) available to every view
        View::share('user', $this->oauth->user());

        return $next($request);
    }
}
