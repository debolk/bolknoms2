<?php

namespace App\Http\Middleware\Api;

use App\Http\Helpers\OAuth as OAuthHelper;
use Closure;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OAuth
{
	/**
	 * Allow a request to proceed only if the client sent a valid OAuth token
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
 	 */
	public function handle(Request $request, Closure $next)
	{
        // Make this middleware inoperable for testing
        if (env('APP_ENV') === 'testing') {
            return $next($request);
        }

        $authorization = $request->header('Authorization', false);
        if (! $authorization) {
            return $this->ajaxError(400, 'missing_authorization', 'Client must send Authorization header');
        }

        $valid = $this->checkToken($authorization);
        if (!$valid) {
            return $this->ajaxError(400, 'authorization_invalid', 'Authorization token is invalid or expired');
        }

        return $next($request);
	}

    /**
     * Check if the supplied token is stil valid
     */
    protected function checkToken($access_token)
    {
        $client = new Client();
        try {
            $url = env('OAUTH_ENDPOINT').'resource/?access_token='.$access_token;
            $response = $client->get($url);
            return $response->getStatusCode() === 200;
        }
        catch(TransferException $e) {
            Log::error("Cannot contact oauth server to validate token: {$e->getMessage()}");
            return false;
        }
        catch (BadResponseException $e) {
            Log::error("Error while communicating with auth server to validate token: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Helper function to construct correctly formatted JSON error responses
     * to AJAX requests
     * @param  integer $httpStatus    HTTP status code to send
     * @param  string  $internalError descriptive error code, e.g. meal_not_found
     * @param  string  $message       line of text to explain error state to end users
     * @return Illuminate\Http\Response
     */
    protected function ajaxError($httpStatus, $internalError, $message)
    {
        return response()->json([
            'error' => $internalError,
            'error_details' => $message,
        ], $httpStatus);
    }
}
