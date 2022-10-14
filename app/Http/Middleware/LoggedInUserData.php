<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class LoggedInUserData
{
    public function handle(Request $request, Closure $next): mixed
    {
        // Make the current user data (or null) available to every view
        View::share('user', Auth::user());

        return $next($request);
    }
}
