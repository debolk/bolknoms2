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
        return redirect($result);
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

    /**
     * Redirects to a photo of the user
     * @return Redirect
     */
    public function photo()
    {
        return redirect(OAuth::user()->photoURL);
    }

    /**
     * Overwrite the handicap of a user
     */
    public function setHandicap()
    {
        OAuth::user()->updateHandicap(Request::get('handicap'));
        return response(null, 200);
    }
}
