<?php

namespace App\Http\Helpers;

use Session;
use App;
use GuzzleHttp\Client;

class OAuth
{
    /**
     * Return whether we have a valid session
     * @access public
     * @static
     * @return boolean
     */
    public static function valid()
    {
        // Must have a token
        if (! Session::has('oauth.token')) {
            return false;
        }

        // Refresh the token if needed
        if (self::tokenIsExpired()) {
            return self::refreshToken();
        }

        return self::tokenIsValid();
    }

    /**
     * Returns the current user details or null if none
     * @return App\Models\User
     */
    public static function user()
    {
        if (! OAuth::valid()) {
            return null;
        }

        // Refresh details if needed
        if (Session::get('oauth.user_info', null) === null) {
            var_dump('hasrefreshed');
            self::retrieveDetails();
        }

        $id       = Session::get('oauth.user_info', null)->id;
        $name     = Session::get('oauth.user_info', null)->name;
        $photoURL = Session::get('oauth.user_info', null)->photoURL;
        return new App\Models\User($id, $name, $photoURL);
    }

    /**
     * Get the details of this user
     */
    private static function retrieveDetails()
    {
        $user = new \stdClass();
        $client = new Client();
        $token = Session::get('oauth.token')->access_token;

        // Get the user ID
        $url = env('OAUTH_ENDPOINT').'resource/?access_token='.$token;
        $response = $client->get($url);
        $user->id = $response->json()['user_id'];

        // Get full name
        $url = 'https://people.debolk.nl/persons/'.$user->id.'/name?access_token='.$token;
        $response = $client->get($url);
        $user->name = $response->json()['name'];

        // Get picture
        $user->photoURL = 'https://people.debolk.nl/persons/'.$user->id.'/photo/128/128?access_token='.$token;

        // Store data
        Session::set('oauth.user_info', $user);
        Session::save();
    }

    /**
     * Returns whether the token is not expired
     * @access private
     * @static
     * @return boolean
     */
    private static function tokenIsExpired()
    {
        $now = new \DateTime();
        $expiration = (new \DateTime())->setTimestamp(Session::get('oauth.token')->expires_at);

        return ($expiration <= $now);
    }

    /**
     * Refreshes the token
     * THIS FUNCTION MAY APP::ABORT()
     * @access private
     * @static
     * @return void
     */
    private static function refreshToken()
    {
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
            Session::remove('oauth');
        }

        $token->expires_at = strtotime('+' . (((int)$token->expires_in) - 100) . ' seconds');

        // Overwrite the token with the new token
        Session::put('oauth.token', $token);
    }

    /**
     * Redirect the client to the authorisation server to login
     * @return Redirect
     */
    public static function toAuthorisationServer($original_route)
    {
        // Store the URL we attempt to visit
        Session::put('oauth.goal', $original_route);

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

    /**
     * Returns whether the token is valid
     * @access private
     * @static
     * @return boolean
     */
    private static function tokenIsValid()
    {
        $request = curl_init();
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request,CURLOPT_URL, env('OAUTH_ENDPOINT').'resource/?access_token='.Session::get('oauth.token')->access_token);
        curl_exec($request);
        $status = curl_getinfo($request, CURLINFO_HTTP_CODE);
        curl_close($request);
        return ($status === 200);
    }

    /**
     * Check whether the current user has board-level permissions
     * @access public
     * @static
     * @return boolean
     */
    public static function isBoardMember()
    {
        $request = curl_init();
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request,CURLOPT_URL, env('OAUTH_ENDPOINT').'bestuur/?access_token='.Session::get('oauth.token')->access_token);
        $result = curl_exec($request);
        $status = curl_getinfo($request, CURLINFO_HTTP_CODE);
        curl_close($request);
        return ($status === 200);
    }

    /**
     * Forget the currently logged-in user
     * @return void
     */
    public static function logout()
    {
        Session::remove('oauth');
    }

    /**
     * Process the OAuth authorisation callback, storing the session
     * THIS FUNCTION MAY APP::ABORT()
     * @static
     * @access public
     * @param  array $input Input::get() is the only acceptable input here
     * @return string a URL to redirect to
     */
    public static function processCallback($input)
    {
        // Check state to prevent CSRF
        if ((string)$input['state'] !== (string)Session::get('oauth.state')) {
            App::abort(400, 'OAuth state mismatch');
        }

        // Check for errors
        if (isset($input['error'])) {
            // Denying permission is not actually an error, redirect to frontpage
            if ($input['error'] === 'access_denied') {
                return '/';
            }
            else {
                Session::remove('oauth');
                App::abort(500, $input['error_description']);
            }
        }

        // Retrieve access code
        $request = curl_init();
        $fields = [
            'grant_type' => 'authorization_code',
            'code' => $input['code'],
            'redirect_uri' => env('OAUTH_CALLBACK'),
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
            Session::remove('oauth');
            App::abort(500, $token->error_description);
        }

        // Determine expiry time (-100 seconds to be sure)
        $token->expires_at = strtotime('+' . (((int)$token->expires_in) - 100) . ' seconds');

        // Store the token
        Session::put('oauth.token', $token);

        // Redirect to the original URL
        return Session::get('oauth.goal');

    }
}
