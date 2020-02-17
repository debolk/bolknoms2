<?php

namespace App\Http\Helpers;

use App;
use App\Http\Helpers\ProfilePicture;
use App\Models\User;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OAuth
{
    /**
     * Session storage to use
     * @var \Illuminate\Support\Facades\Session
     */
    private $session;

    /**
     * Return whether we have a valid session
     * @return boolean
     */
    public function valid()
    {
        // Must have a token
        if (! Session::has('oauth.token')) {
            return false;
        }

        // Refresh the token if needed
        if ($this->tokenIsExpired()) {
            $this->refreshToken();
        }

        // We assume a token is valid for its lifetime
        // as checking it takes too long (800+ ms)
        return true;
    }

    /**
     * Returns the current user details or null if none
     */
    public function user(): ?User
    {
        // Must have a valid session
        if (! $this->valid()) {
            return null;
        }

        // Find the current user
        if (Session::has('oauth.current_user')) {
            return User::find(Session::get('oauth.current_user'));
        }

        // Upsert the user
        return $this->upsertUser();
    }

    /**
     * Return a valid access token for use in OAuth2-protected calls
     * @return string|null
     */
    public function getAccessToken()
    {
        if (! $this->valid()) {
            return null;
        }

        return Session::get('oauth.token')->access_token;
    }

    /**
     * Create or update the current user in the database and return it
     * @return \App\Models\User
     */
    private function upsertUser()
    {
        $client = new Client();
        $access_token = Session::get('oauth.token')->access_token;

        // Get the username
        $username = null;
        try {
            $url = config('oauth.endpoint') . 'resource/?access_token=' . $access_token;
            $response = $client->get($url);
            $username = json_decode($response->getBody())->user_id;
        } catch (\Exception $e) {
            Bugsnag::notifyException($e);
            $this->fatalError("OAuth authorisation server not okay", $e->getMessage(), 502);
        }

        // We are upserting the current db entry
        $user = User::where(['username' => $username])->first();
        if (! $user) {
            $user = new User();
            $user->username = $username;
        }

        // Get the details of the user
        try {
            $url = 'https://people.debolk.nl/persons/' . $user->username . '/basic?access_token=' . $access_token;
            $response = $client->get($url);
            $user_data = json_decode($response->getBody());

            $user->name = $user_data->name;
            $user->email = $user_data->email;
        } catch (\Exception $e) {
            Bugsnag::notifyException($e);
            // Ignore, we process missing information below
        }

        // Validate the user object for necessary properties
        if ($user->email == null || $user->username == null || $user->name == null) {
            $this->fatalError('Could not retrieve crucial details of your user account', 'user object misses required values', 502);
        }

        // Save to database
        if (! $user->save()) {
            $this->fatalError('Could not persist your account details', 'cannot persist user', 500);
        }

        // Store the user in session
        Session::put('oauth.current_user', $user->id);
        Session::save(); // An explicit save is required in middleware

        return $user;
    }

    /**
     * Returns whether the token is not expired
     * @access private
     * @return boolean
     */
    private function tokenIsExpired()
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
     * @return void
     */
    private function refreshToken()
    {
        $response = null;
        try {
            Log::debug('Refreshing token ' . Session::get('oauth.token')->refresh_token);
            $client = new Client();
            $response = $client->post(config('oauth.endpoint') . 'token/', ['json' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => Session::get('oauth.token')->refresh_token,
                'client_id' => config('oauth.client.id'),
                'client_secret' => config('oauth.client.secret'),
            ]]);
        } catch (ClientException $exception) {
            // Test for invalid grants, which means our refresh token has expired
            if ($exception->hasResponse()) {
                $response = json_decode((string) $exception->getResponse()->getBody());
                if ($response->error === 'invalid_grant') {
                    $this->logout('Je huidige inlog is verlopen');
                    return;
                }
            }
            throw $exception;
        } catch (\Exception $e) {
            Bugsnag::notifyException($e);
            $this->fatalError('cannot refresh token', $e->getMessage(), 502);
        }

        $token = json_decode($response->getBody());

        // Do not proceed if we encounter an error
        if (isset($token->error) || isset($token->error_description)) {
            $this->fatalError('refreshed token not valid', $token->error_description, 502);
        }

        // Calculate expiration date of token
        $token->created_at = new \DateTime();
        $token->expires_at = new \DateTime("+{$token->expires_in} seconds");

        // Overwrite the token with the new token
        Session::put('oauth.token', $token);
        Session::save();
    }

    /**
     * Redirect the client to the authorisation server to login
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toAuthorisationServer($original_route)
    {
        // Store the URL we attempt to visit
        Session::put('oauth.goal', $original_route);

        // Generate a random six digit number as state to defend against CSRF-attacks
        $state = rand(100000, 999999);
        Session::put('oauth.state', $state);

        // For some reason, an explicit save is needed in middleware
        Session::save();

        // Redirect to the oauth endpoint for authentication
        $query_string = http_build_query([
            'response_type' => 'code',
            'client_id' => config('oauth.client.id'),
            'redirect_uri' => config('oauth.callback'),
            'state' => $state,
        ]);
        return redirect(config('oauth.endpoint') . 'authenticate/?' . $query_string);
    }

    /**
     * Check whether the current user has board-level permissions
     * @access public
     * @return boolean
     */
    public function isBoardMember()
    {
        try {
            $client = new Client();
            $url = config('oauth.endpoint') . 'bestuur/?access_token=' . Session::get('oauth.token')->access_token;
            $request = $client->get($url);
            return ($request->getStatusCode() === 200);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Logout the current user, optionally showing a message popup
     * @param  string $message optional
     */
    public function logout(string $message = 'Je bent uitgelogd')
    {
        $this->purgeSession();

        Session::flash('action_result', ['status' => 'success', 'message' => $message]);
        throw new HttpException(301, $message, null, ['Location' => '/']);
    }

    /**
     * Process the OAuth authorisation callback, storing the session
     * @access public
     * @param  array $input Input::get() is the only acceptable input here
     * @return string a URL to redirect to
     */
    public function processCallback($input)
    {
        // Check state to prevent CSRF
        if ((string)$input['state'] !== (string)Session::get('oauth.state')) {
            // Log out the user and send to error page
            $this->purgeSession();
            abort(400, 'state mismatch');
        }

        // Check for errors
        if (isset($input['error'])) {
            // Denying permission is not actually an error, redirect to frontpage
            if ($input['error'] === 'access_denied') {
                return '/';
            } else {
                $this->fatalError('fatal error while processing callback', $input['error_description'], 500);
            }
        }

        // Retrieve access code
        $client = new Client();
        $result = $client->post(config('oauth.endpoint') . 'token/', [
            'json' => [
                'grant_type' => 'authorization_code',
                'code' => $input['code'],
                'redirect_uri' => config('oauth.callback'),
                'client_id' => config('oauth.client.id'),
                'client_secret' => config('oauth.client.secret'),
            ],
        ]);

        $token = json_decode($result->getBody());

        // Do not proceed if we encounter an error
        if (isset($token->error) || isset($token->error_description)) {
            $this->fatalError('Access token invalid', $token->error_description, 502);
        }

        // Determine expiry time
        $token->created_at = new \DateTime();
        $token->expires_at = new \DateTime("+{$token->expires_in} seconds");

        // Store the token
        Session::put('oauth.token', $token);

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
    private function fatalError($technical, $logged_error = null, $status_code = 500)
    {
        // Log the appropriate error message
        if ($logged_error !== null) {
            Log::error($logged_error);
        }

        Bugsnag::notifyError('OAuthFatalError', $technical, function ($report) use ($technical, $logged_error,$status_code) {
            $report->setMetaData(compact('technical', 'logged_error', 'status_code'));
        });

        // Log out the current user
        $this->purgeSession();

        // Send a nice error page with explanation
        abort($status_code, $technical);
    }

    private function purgeSession()
    {
        Session::remove('oauth');
        Session::remove('oauth.state');
        Session::remove('oauth.token');
        Session::remove('oauth.goal');
        Session::remove('oauth.current_user');
        Session::save();
    }
}
