<?php

namespace App\Http\Helpers;

use Session;
use App;

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
     * Returns the current user or null if none
     * @return string nullable
     */
    public static function currentUsername()
    {
        if (self::valid()) {
            return Session::get('oauth.token.user_id');
        }
        else {
            return null;
        }
    }

    /**
     * Returns whether the token is not expired
     * @access private
     * @static
     * @return boolean
     */
    private static function tokenIsExpired()
    {
        return (Session::get('oauth.token')->expires_at <= time());
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

        // Determine expiry time (-100 seconds to be sure)
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
     * @return Response or Redirect
     */
    public static function processCallback($input)
    {
        // Check state to prevent CSRF
        if ((string)$input['state'] !== (string)Session::get('oauth.state')) {
            App::abort(400, 'OAuth state mismatch');
        }

        // Check for errors
        if (isset($input['error'])) {
            // Show a helpful page if the user denied permission
            if ($input['error'] === 'access_denied') {
                return $this->setPageContent(view('oauth/denied', ['url' => Session::get('oauth.goal')]));
            }
            else {
                App::abort(500, $input['error_description']);
                Session::remove('oauth');
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
            App::abort(500, $token->error_description);
            Session::remove('oauth');
        }

        // Determine expiry time (-100 seconds to be sure)
        $token->expires_at = strtotime('+' . (((int)$token->expires_in) - 100) . ' seconds');

        // Store the token
        Session::put('oauth.token', $token);

        // Redirect to the original URL
        return redirect(Session::get('oauth.goal'));
    }
}
