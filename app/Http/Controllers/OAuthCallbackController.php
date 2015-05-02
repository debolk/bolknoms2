<?php

namespace App\Http\Controllers;

use App;
use Request;
use Session;

class OAuthCallbackController extends ApplicationController
{
    /**
     * Store the callback
     * @return View
     */
    public function callback()
    {
        // Check state to prevent CSRF
        if ((string)Request::get('state') !== (string)Session::get('oauth.state')) {
            App::abort(400, 'OAuth state mismatch');
        }

        // Check for errors
        $error = Request::get('error', null);
        if ($error !== null) {
            // Show a helpful page if the user denied permission
            if ($error === 'access_denied') {
                return $this->setPageContent(view('oauth/denied', ['url' => Session::get('oauth.goal')]));
            }
            else {
                App::abort(500, Request::get('error_description'));
                Session::flush();
            }
        }

        // Retrieve access code
        $request = curl_init();
        $fields = [
            'grant_type' => 'authorization_code',
            'code' => Request::get('code'),
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
            Session::flush();
        }

        // Determine expiry time (-100 seconds to be sure)
        $token->expires_at = strtotime('+' . (((int)$token->expires_in) - 100) . ' seconds');

        // Store the token
        Session::put('oauth.token', $token);

        // Redirect to the original URL
        return redirect(Session::get('oauth.goal'));
    }

    /**
     * Print the current username logged in, or NULL if none is found
     * @return string the current username
     */
    public function currentUser()
    {
        $user = Session::get('oauth.user_id');
        if ($user) {
            return response($user, 200);
        }
        else {
            return response(null, 204);
        }
    }

    /**
     * Forces a OAuth user login (through route middleware), than redirects to registration
     * @return Redirect /
     */
    public function login()
    {
        return redirect('/');
    }

    /**
     * Logs out the current user
     * @return Redirect to previous page
     */
    public function logout()
    {
        Session::flush();
        redirect()->back();
    }
}
