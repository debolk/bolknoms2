<?php

namespace App\Http\Middleware;

use App\Http\Helpers\OAuth as OAuthHelper;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class Board
{
    /**
     * Allow a request to proceed only if we have board-level permissions
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! Auth::check()) {
            abort(500, 'Attempted board authorization without a valid session');
        }

        if (Auth::user()->is_board) {
            // Proceed with request
            return $next($request);
        } else {
            abort(403, 'Access denied: you\'re not authorized to access this');
        }
    }
}
