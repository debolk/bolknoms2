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
        if (! Session::has('oauth.token')) {
            App::abort(500, 'Attempted board authorization without a valid session');
        }

        if ($this->checkBoardPermission()) {
            // Proceed with request
            return $next($request);
        }
        else {
            App::abort(403, 'Access denied: you\'re not authorized to access this');
        }
	}

    /**
     * Call the authorization server to check if this user has board-level authorization
     * @return boolean true if allowed
     */
    private function checkBoardPermission()
    {
        $request = curl_init();
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request,CURLOPT_URL, env('OAUTH_ENDPOINT').'bestuur/?access_token='.Session::get('oauth.token')->access_token);
        $result = curl_exec($request);
        $status = curl_getinfo($request, CURLINFO_HTTP_CODE);
        curl_close($request);
        return ($status === 200);
    }

}
