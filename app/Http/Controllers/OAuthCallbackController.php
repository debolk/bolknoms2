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
        if ((string)Request::get('state') !== (string)Session::get('oauth_state')) {
            App::abort(400, 'OAuth state mismatch');
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
        $result = json_decode(curl_exec($request));

        // Do not proceed if we encounter an error
        if (isset($result->error)) {
            App::abort(500, $result->error_description);
        }

        // Store access code
        Session::put('oauth_access_token', $result->access_token);

        // Redirect to the original URL
        return redirect()->action(Session::get('oauth_goal'));
    }
}
