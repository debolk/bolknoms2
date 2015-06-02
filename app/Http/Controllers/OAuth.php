<?php

namespace App\Http\Controllers;

use App;
use Request;
use App\Http\Helpers\OAuth as OAuthHelper;

class OAuth extends Application
{
    /**
     * Store the callback
     * @return View
     */
    public function callback()
    {
        $result = OAuthHelper::processCallback(Request::all());
        return redirect($result);
    }

    public function login()
    {
        return OAuthHelper::toAuthorisationServer('/');
    }

    /**
     * Logs out the current user
     * @return Redirect to previous page
     */
    public function logout()
    {
        OAuthHelper::logout();
        return redirect('/');
    }

    /**
     * Redirects to a photo of the user
     * @return Redirect
     */
    public function photo()
    {
        return redirect(OAuthHelper::user()->photoURL);
    }

    /**
     * Overwrite the handicap of a user
     */
    public function setHandicap()
    {
        OAuthHelper::user()->updateHandicap(Request::get('handicap'));
        return response(null, 200);
    }
}
