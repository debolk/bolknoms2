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
        // Store the URL we attempt to visit
        Session::put('oauth_goal', $request->route()->getActionName());

        // Determine if we have a token
        if (Session::has('oauth_access_token')) {
            $valid = $this->validateToken();

            // Continue if the resource is acceptable
            if ($valid) {
                // Proceed with request
                return $next($request);
            }
            else {
                App::abort(403, 'Access denied: you\'re not authorized to access this');
            }
        }
        else {
            // Generate a random six digit number as state to defend against CSRF-attacks
            $state = rand(pow(10, 5), pow(10, 6)-1);
            Session::put('oauth_state', $state);

            // Redirect to the oauth endpoint for authentication
            $query_string = http_build_query([
                'response_type' => 'code',
                'client_id' => env('OAUTH_CLIENT_ID'),
                'client_pass' => env('OAUTH_CLIENT_SECRET'),
                'redirect_uri' => env('OAUTH_CALLBACK'),
                'state'=> $state,
            ]);
            return redirect(env('OAUTH_ENDPOINT').'authorize/?'.$query_string);
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
        curl_setopt($request,CURLOPT_URL, env('OAUTH_ENDPOINT').'bestuur/?access_token='.Session::get('oauth_access_token'));
        $result = curl_exec($request);
        $status = curl_getinfo($request, CURLINFO_HTTP_CODE);
        curl_close($request);
        return ($status === 200);
    }

}
