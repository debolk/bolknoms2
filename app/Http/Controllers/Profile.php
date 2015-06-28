<?php

namespace App\Http\Controllers;

use App\Http\Helpers\OAuth;
use \Request;

class Profile extends Application
{
    /**
     * Show a list of all eaters
     */
    public function index()
    {
        return $this->setPageContent(view('profile/index', ['user' => OAuth::user()]));
    }

    /**
     * Overwrite the handicap of a user
     */
    public function setHandicap()
    {
        $user = OAuth::user();
        $user->handicap = Request::get('handicap');
        $user->save();
        return response(null, 200);
    }
}
