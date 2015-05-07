<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Http\Helpers\OAuth;

class SplashController extends ApplicationController
{
    /**
      * Show the login splashscreen
      * @return View
      */
    public function index()
    {
        return view('splash/index');
    }

    /**
     * Confirms the user has a valid session
     * @return boolean
     */
    public function isLoggedIn()
    {
        $status = (OAuth::valid()) ? (200) : (205);
        return response(null, $status);
    }

    /**
     * Shows instructions for registering without a Bolk-account
     * @return View
     */
    public function noAccount()
    {
        return view('splash/geenaccount', ['meal' => Meal::today()]);
    }
}
