<?php

namespace App\Http\Helpers;

use Session;
use App;
use GuzzleHttp\Client;
use App\Models\User;

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
            self::refreshToken();
        }

        return self::tokenIsValid();
    }

    /**
     * Returns the current user details or null if none
     * @return App\Models\User
     */
    public static function user()
    {
        // Must have a valid session
        if (! OAuth::valid()) {
            return null;
        }

        // Find the current user
        if (Session::has('oauth.current_user')) {
            return User::find(Session::get('oauth.current_user'));
        }

        // Upsert the user
        return self::upsertUser();
    }

    /**
     * Create or update the current user in the database and return it
     * @return \App\Models\User
     */
    private static function upsertUser()
    {
        $client = new Client();
        $access_token = Session::get('oauth.token')->access_token;

        // Get the username
        try {
            $url = env('OAUTH_ENDPOINT').'resource/?access_token='.$access_token;
            $response = $client->get($url);
            $username = json_decode($response->getBody())->user_id;
        }
        catch (\Exception $e) {
            self::fatalError($e->getMessage(), "OAuth authorisation server not okay", 502);
        }

        // We are upserting the current db entry
        $user = User::findOrNew(['username' => $username]);

        // Get the details of the user
        try {
            $url = 'https://people.debolk.nl/persons/'.$user->username.'/basic?access_token='.$access_token;
            $response = $client->get($url);
            $user_data = json_decode($response->getBody());

            $user->name = $user_data->name;
            $user->email = $user_data->email;
        }
        catch (\Exception $e) {
            // Ignore, we process missing information below
        }

        // Validate the user object for necessary properties
        if ($user->email == null || $user->username == null || $user->name == null) {
            self::fatalError('user object misses required values', 'Could not retrieve crucial details of your user account', 502);
        }

        // Save to database
        if (! $user->save()) {
            self::fatalError('cannot persist user', 'Could not persist your account details', 500);
        }

        // Store the user in session
        Session::set('oauth.current_user', $user->id);
        Session::save();

        return $user;
    }

    public static function photoURL()
    {
        // Must have a valid session
        if (! OAuth::valid()) {
            return null;
        }

        $user = self::user();
        $access_token = Session::get('oauth.token')->access_token;
        return 'https://people.debolk.nl/persons/'.$user->username.'/photo/128/128?access_token='.$access_token;
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
        $expiry = Session::get('oauth.token')->expires_at;

        // Subtract one minute to allow for clock drift
        $expiry = $expiry->sub(new \DateInterval('PT1M'));

        return ($expiry <= $now);
    }

    /**
     * Refreshes the token
     * @access private
     * @static
     * @return void
     */
    private static function refreshToken()
    {
        try {
            $client = new Client();
            $response = $client->post(env('OAUTH_ENDPOINT').'token/', ['json' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => Session::get('oauth.token')->refresh_token,
                'client_id' => env('OAUTH_CLIENT_ID'),
                'client_secret' => env('OAUTH_CLIENT_SECRET'),
            ]]);
        }
        catch (\Exception $e) {
            self::fatalError('Cannot refresh token', $e->getMessage());
        }

        $token = json_decode($response->getBody());

        // Do not proceed if we encounter an error
        if (isset($token->error)) {
            self::fatalError('Refreshed token not valid', $token->error_description);
        }

        // Calculate expiration date of token
        $token->created_at = new \DateTime();
        $token->expires_at = new \DateTime("+{$token->expires_in} seconds");

        // Overwrite the token with the new token
        Session::set('oauth.token', $token);
    }

    /**
     * Redirect the client to the authorisation server to login
     * @return Redirect
     */
    public static function toAuthorisationServer($original_route)
    {
        // Store the URL we attempt to visit
        Session::set('oauth.goal', $original_route);

        // Generate a random six digit number as state to defend against CSRF-attacks
        $state = rand(pow(10, 5), pow(10, 6)-1);
        Session::set('oauth.state', $state);

        // For some reason, an explicit save is needed in middleware
        Session::save();

        // Redirect to the oauth endpoint for authentication
        $query_string = http_build_query([
            'response_type' => 'code',
            'client_id' => env('OAUTH_CLIENT_ID'),
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
        try {
            $client = new Client();
            $url = env('OAUTH_ENDPOINT').'resource/?access_token='.Session::get('oauth.token')->access_token;
            $request = $client->get($url);
            return ($request->getStatusCode() === 200);
        }
        catch(\Exception $e) {
            return false;
        }
    }

    /**
     * Check whether the current user has board-level permissions
     * @access public
     * @static
     * @return boolean
     */
    public static function isBoardMember()
    {
        try {
            $client = new Client();
            $url = env('OAUTH_ENDPOINT').'bestuur/?access_token='.Session::get('oauth.token')->access_token;
            $request = $client->get($url);
            return ($request->getStatusCode() === 200);
        }
        catch (\Exception $e) {
            return false;
        }
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
     * @static
     * @access public
     * @param  array $input Input::get() is the only acceptable input here
     * @return string a URL to redirect to
     */
    public static function processCallback($input)
    {
        // Check state to prevent CSRF
        if ((string)$input['state'] !== (string)Session::get('oauth.state')) {
            self::fatalError('State mismatch');
        }

        // Check for errors
        if (isset($input['error'])) {
            // Denying permission is not actually an error, redirect to frontpage
            if ($input['error'] === 'access_denied') {
                return '/';
            }
            else {
                self::fatalError('fatal error while processing callback', $input['error_description']);
            }
        }

        // Retrieve access code
        try {
            $client = new Client();
            $result = $client->post(env('OAUTH_ENDPOINT').'token/', [
                'json' => [
                    'grant_type' => 'authorization_code',
                    'code' => $input['code'],
                    'redirect_uri' => env('OAUTH_CALLBACK'),
                    'client_id' => env('OAUTH_CLIENT_ID'),
                    'client_secret' => env('OAUTH_CLIENT_SECRET'),
                ],
            ]);
        }
        catch (\Exception $e) {
            self::fatalError('Cannot trade authorisation token for access token', $e->getMessage());
        }

        $token = json_decode($result->getBody());

        // Do not proceed if we encounter an error
        if (isset($token->error)) {
            self::fatalError('Access token invalid', $token->error_description);
        }

        // Determine expiry time
        $token->created_at = new \DateTime();
        $token->expires_at = new \DateTime("+{$token->expires_in} seconds");

        // Store the token
        Session::set('oauth.token', $token);

        // Redirect to the original URL
        return Session::get('oauth.goal');

    }

    /**
     * End the session and provide an explanation to the user
     * THIS FUNCTION WILL APP::ABORT
     * @param  string $technical    message to show
     * @param  string $logged_error optional string to write to log files
     * @param  int    $status_code  optional the HTTP status code to send, defaults to 500
     * @return void
     */
    private static function fatalError($technical, $logged_error = null, $status_code = 500)
    {
        if ($logged_error !== null) {
            \Log::error($logged_error);
        }
        Session::remove('oauth');
        App::abort($status_code, 'Er ging iets fout met het ophalen van informatie van je gebruikersaccount. Om veiligheidsredenen ben je automatisch uitgelogd. Je kunt opnieuw inloggen en het nogmaals proberen. Technische details: ' . $technical);
    }
}
