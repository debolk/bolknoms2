<?php

namespace App\Http\Middleware;

use App\Http\Helpers\OAuth as OAuthHelper;
use Closure;
use Illuminate\Http\Request;

class OAuth
{
    /**
     * @var App\Http\Helpers\OAuth
     */
    private $oauth;

    /**
     * @param App\Http\Helpers\OAuth $oauth
     */
    public function __construct(OAuthHelper $oauth)
    {
        $this->oauth = $oauth;
    }

	/**
	 * Allow a request to proceed only if we hold a valid OAuth token
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
 	 */
	public function handle(Request $request, Closure $next)
	{
        // Make this middleware inoperable for testing
        if (env('APP_ENV') === 'testing') {
            return $next($request);
        }

        if ($this->oauth->valid()) {
            return $next($request);
        }
        else {
            return $this->oauth->toAuthorisationServer($request->route()->getUri());
        }

	}
}
