<?php

/**
 * Filter that forces the user to authenticate with De Bolk OAuth2 endpoint
 */
class OAuthFilter
{
    /**
     * Perform the filter, authenticating the user if needed
     */
    public function filter($route, $request)
    {
        // Store the URL we attempt to visit
        Session::put('oauth_goal', $route->getActionName());

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
     * @return Redirect
     */
    private function authenticate()
    {
        // Generate a random six digit number as state to defend against CSRF-attacks
        $state = rand(pow(10, 5), pow(10, 6)-1);
        Session::put('oauth_state', $state);

        // Build the authentication URL from configuration
        $query_string = http_build_query([
            'response_type' => 'code',
            'client_id' => getenv('OAUTH_CLIENT_ID'),
            'client_pass' => getenv('OAUTH_CLIENT_SECRET'),
            'redirect_uri' => getenv('OAUTH_CALLBACK'),
            'state'=> $state,
        ]);
        $url = getenv('OAUTH_ENDPOINT').'authorize/?'.$query_string;

        // Redirect to the constructed URL
        return Redirect::to($url);
    }

    /**
     * Determine whether a user is authorized to access this system
     * @return void if succesfull (continues the view building) or App::abort(403) on failure
     */
    public function authorize()
    {
        // Get resource status code
        $request = curl_init();
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request,CURLOPT_URL, getenv('OAUTH_ENDPOINT').'bestuur/?access_token='.Session::get('oauth_access_token'));
        $result = curl_exec($request);
        $status = curl_getinfo($request, CURLINFO_HTTP_CODE);
        curl_close($request);

        // Continue if the resource is acceptable
        if ($status === 200) {
            return;
        }
        else {
            App::abort(403, 'Access denied: you\'re not authorized to access this');
        }
    }

    /**
     * Provides the callback function for the authentication code
     * retrieves the access code, stores it and redirects to the original URL
     * @return Redirect
     */
    public function callback()
    {
        // Check state to prevent CSRF
        if ((string)Input::get('state') !== (string)Session::get('oauth_state')) {
            App::abort(400, 'OAuth state mismatch');
        }

        // Retrieve access code
        $request = curl_init();
        $fields = [
            'grant_type' => 'authorization_code',
            'code' => Input::get('code'),
            'redirect_uri' => getenv('OAUTH_CALLBACK'),
            'client_id' => getenv('OAUTH_CLIENT_ID'),
            'client_secret' => getenv('OAUTH_CLIENT_SECRET'),
        ];
        curl_setopt($request,CURLOPT_URL, getenv('OAUTH_ENDPOINT').'token/');
        curl_setopt($request,CURLOPT_POST, count($fields));
        curl_setopt($request,CURLOPT_POSTFIELDS, http_build_query($fields));
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        $result = json_decode(curl_exec($request));

        // Check if the authentication hasn't expired
        if (! isset($result->access_token)) {
            App::abort(500, $result->error);
        }

        // Store access code
        Session::put('oauth_access_token', $result->access_token);

        // Redirect to the original URL
        return Redirect::action(Session::get('oauth_goal'));
    }
}
