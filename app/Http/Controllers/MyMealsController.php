<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Request;
use Session;

class MyMealsController extends ApplicationController
{
    /**
      * Show the index that allows users to quickly register for the upcoming meal
      * @return View
      */
    public function index()
    {
        $user = Session::get('oauth.user_id');
        return $this->setPageContent(view('mymeals/index', ['registrations' => Registration::forUser($user)->get()]));
    }

}
