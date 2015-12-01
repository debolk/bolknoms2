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
        return redirect(OAuthHelper::photoURL());
    }

    /**
     * Redirects to a photo of the user
     * @param  string $username
     * @return Illuminate\Support\Facades\Redirect
     */
    public function photoFor($username)
    {
        return redirect(OAuthHelper::photoURLFor($username));
    }
}
