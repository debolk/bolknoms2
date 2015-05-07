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
        return OAuth::processCallback(Request::all());
    }

    /**
     * Logs out the current user
     * @return Redirect to previous page
     */
    public function logout()
    {
        OAuth::logout();
        redirect()->back();
    }
}
