<?php namespace App\Http\Middleware;

use Closure;
use Session;
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
        // Determine if we have a token
        if (Session::has('oauth.token')) {

            // Refresh if needed
            $this->refreshExpiredToken();

            // Validate token
            if ($this->validateToken()) {
                // Proceed with request
                return $next($request);
            }
            else {
                App::abort(403, 'Access denied: you\'re not authorized to access this');
                Session::flush();
            }
        }
        else {
            // Store the URL we attempt to visit
            Session::put('oauth.goal', $request->route()->getUri());

            // Generate a random six digit number as state to defend against CSRF-attacks
            $state = rand(pow(10, 5), pow(10, 6)-1);
            Session::put('oauth.state', $state);

            // For some reason, an explicit save is needed in middleware
            Session::save();

            // Redirect to the oauth endpoint for authentication
            $query_string = http_build_query([
                'response_type' => 'code',
                'client_id' => env('OAUTH_CLIENT_ID'),
                'client_pass' => env('OAUTH_CLIENT_SECRET'),
                'redirect_uri' => env('OAUTH_CALLBACK'),
                'state'=> $state,
            ]);
            return redirect(env('OAUTH_ENDPOINT').'authenticate/?'.$query_string);
        }
	}

    /**
     * Validate our OAuth token
     * @return boolean true if valid
     */
    private function validateToken()
    {
        $request = curl_init();
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request,CURLOPT_URL, env('OAUTH_ENDPOINT').'resource/?access_token='.Session::get('oauth.token')->access_token);
        $answer = curl_exec($request);
        $status = curl_getinfo($request, CURLINFO_HTTP_CODE);
        curl_close($request);
        $result = json_decode($answer);

        if ($status === 200) {
            Session::put('oauth.user_id', $result->user_id);
            return true;
        }
        return false;
    }

    /**
     * Refresh an access token if it is expired
     * @return void
     */
    private function refreshExpiredToken()
    {
        // Do not refresh a still fresh token
        if (Session::get('oauth.token')->expires_at > time()) {
            return;
        }

        // Send refresh request
        $request = curl_init();
        $fields = [
            'grant_type' => 'refresh_token',
            'refresh_token' => Session::get('oauth.token')->refresh_token,
            'client_id' => env('OAUTH_CLIENT_ID'),
            'client_secret' => env('OAUTH_CLIENT_SECRET'),
        ];
        curl_setopt($request,CURLOPT_URL, env('OAUTH_ENDPOINT').'token/');
        curl_setopt($request,CURLOPT_POST, count($fields));
        curl_setopt($request,CURLOPT_POSTFIELDS, http_build_query($fields));
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        $token = json_decode(curl_exec($request));

        // Do not proceed if we encounter an error
        if (isset($token->error)) {
            App::abort(500, $token->error_description);
            Session::flush();
        }

        // Determine expiry time (-100 seconds to be sure)
        $token->expires_at = strtotime('+' . (((int)$token->expires_in) - 100) . ' seconds');

        // Overwrite the token with the new token
        Session::put('oauth.token', $token);
    }

}
