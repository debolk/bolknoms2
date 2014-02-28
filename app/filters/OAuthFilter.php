<?php

/**
 * Filter that forces the user to authenticate with De Bolk OAuth2 endpoint
 */
class OAuthFilter
{
    /**
     * Start the instance, retrieving the configuration
     */
    public function __construct()
    {
        $this->config = Config::get('app.oauth');
    }

    /**
     * Perform the filter, authenticating the user if needed
     */
    public function filter($route, $request)
    {
        // Store the URL we attempt to visit
        Session::put('oauth_goal', $route);

        // Determine whether we're authenticated
        if (Session::has('oauth_access_token')) {
            return $this->authorize();
        }
        else {
            return $this->authenticate();
        }
    }

    /**
     * Redirect a user to the OAuth provider to authenticate
     */
    private function authenticate()
    {
        // Generate a random six digit number as state to defend against CSRF-attacks
        $state = rand(pow(10, 5), pow(10, 6)-1);
        Session::put('oauth_state', $state);

        // Build the authentication URL from configuration
        $query_string = http_build_query([
            'response_type' => 'code',
            'client_id' => $this->config['client_id'],
            'client_pass' => $this->config['client_secret'],
            'redirect_uri' => $this->config['callback'],
            'state'=> $state,
        ]);
        $url = $this->config['endpoint'].'authorize/?'.$query_string;

        // Redirect to the constructed URL
        return Redirect::to($url);
    }

    public function authorize()
    {
        return 'x';
        // Get resource
        // If resource acceptable
            // redirect to original URL
        // else
            // abort 403, user not authorized to access this
    }

    public function callback()
    {
        // Check state to prevent CSRF
        if (Input::get('state') !== Session::get('oauth_state')) {
            App::abort(400, 'OAuth state mismatch');
        }

        dd('x');

        // Retrieve access code
        //FIXME Fix
        // $request = curl_init();
        // $fields = [
        //     'grant_type' => 'authorization_code',
        //     'code' => Input::get('code'),
        //     'redirect_uri' => $this->config['callback'],
        //     'client_id' => $this->config['client_id'],
        //     'client_secret' => $this->config['client_secret'],
        // ];
        // curl_setopt($request,CURLOPT_URL, $this->config['endpoint'].'token/');
        // curl_setopt($request,CURLOPT_POST, count($fields));
        // curl_setopt($request,CURLOPT_POSTFIELDS, http_build_query($fields));
        // var_dump($request);

        // $result = curl_exec($request);

        // var_dump($result);
        // curl_close($request);
        // return '';

        // Store access code
        //FIXME Implement

        // Redirect to original URL
        return Redirect::to(Session::get('oauth_goal'));
    }
}
