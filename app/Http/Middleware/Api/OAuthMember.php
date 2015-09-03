<?php

namespace App\Http\Middleware\Api;

use Illuminate\Http\Request;
use Closure;
use App;
use GuzzleHttp\Client;
use Exception;

class OAuthMember {

	/**
	 * Allow a request to proceed only for valid members
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
 	 */
	public function handle(Request $request, Closure $next)
	{
        // Access token must be present
        $token = $request->input('access_token');
        if (!$token) {
            return response()->json(['errors' => [[
                'code'    => 'oauth_token_missing',
                'message' => 'Client must sent a valid oauth access token',
                'href'    => env('APPLICATION_URL').'api#error_oauth_token_missing'
            ]]], 400);
        }

        // Validate access token is valid
        try {
            $client = new Client;
            $url = env('OAUTH_ENDPOINT').'bekend/?access_token='.$token;
            $request = $client->get($url);
            if ($request->getStatusCode() === 200) {
                return $next($request);
            }
        }
        catch(Exception $e) {
            // Fallthrough below
        }

        // Not a HTTP 200 OK response, therefore invalid
        return response()->json(['errors' => [[
            'code'    => 'oauth_token_invalid',
            'message' => 'OAuth token was given, but not valid for members',
            'href'    => env('APPLICATION_URL').'api#error_oauth_token_invalid'
        ]]], 403);
	}
}
