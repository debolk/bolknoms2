<?php

namespace App\Http\Controllers;

use App;
use Request;
use App\Http\Helpers\OAuth;

class OAuthController extends ApplicationController
{
    /**
     * Store the callback
     * @return View
     */
    public function callback()
    {
        $result = OAuth::processCallback(Request::all());

        if ($result === 'access_denied') {
            return redirect('/');
        }
        else {
            return redirect($result);
        }
    }

    public function login()
    {
        return OAuth::toAuthorisationServer('/');
    }

    /**
     * Logs out the current user
     * @return Redirect to previous page
     */
    public function logout()
    {
        OAuth::logout();
        return redirect('/');
    }

    public function currentUser()
    {
        return response(null, OAuth::valid() ? 200 : 204);
    }
}
