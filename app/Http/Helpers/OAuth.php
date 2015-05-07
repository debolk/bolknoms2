<?php

namespace App\Http\Helpers;

use Session;

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
        if (! Session::has('oauth')) {
            return false;
        }

        // Refresh the token if needed
        if (self::tokenIsExpired()) {
            return self::refreshToken();
        }

        return self::tokenIsValid();
    }

    /**
     * Returns whether the token is not expired
     * @access private
     * @static
     * @return boolean
     */
    private static function tokenIsExpired()
    {

    }

    /**
     * Refreshes the token
     * @access private
     * @static
     * @return boolean whether refreshing succeeded
     */
    private static function refreshToken()
    {

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
}
