<?php

namespace App\Http\Middleware;

use Closure;
use App;

class OAuth {

	/**
	 * Allow a request to proceed only if we hold a valid OAuth token
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
 	 */
	public function handle($request, Closure $next)
	{
        if (\App\Http\Helpers\OAuth::valid()) {
            return $next($request);
        }
        else {
            return \App\Http\Helpers\OAuth::toAuthorisationServer($request->route()->getUri());
        }

	}
}
