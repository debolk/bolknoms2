<?php namespace App\Http\Middleware;

use App;
use App\Http\Helpers\OAuth;
use Closure;
use Session;

class Board
{
    /**
     * @var App\Http\Helpers\OAuth
     */
    private $oauth;

    /**
     * @param App\Http\Helpers\OAuth $oauth
     */
    public function __construct(OAuth $oauth)
    {
        $this->oauth = $oauth;
    }

	/**
	 * Allow a request to proceed only if we have board-level permissions
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        if (! $this->oauth->valid()) {
            abort(500, 'Attempted board authorization without a valid session');
        }

        if ($this->oauth->isBoardMember()) {
            // Proceed with request
            return $next($request);
        }
        else {
            abort(403, 'Access denied: you\'re not authorized to access this');
        }
	}
}
