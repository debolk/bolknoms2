<?php namespace App\Http\Middleware;

use Closure;
use Session;
use App;

class Board {

	/**
	 * Allow a request to proceed only if we have board-level permissions
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        if (! App\Http\Helpers\OAuth::valid()) {
            App::abort(500, 'Attempted board authorization without a valid session');
        }

        if (App\Http\Helpers\OAuth::isBoardMember()) {
            // Proceed with request
            return $next($request);
        }
        else {
            App::abort(403, 'Access denied: you\'re not authorized to access this');
        }
	}
}
