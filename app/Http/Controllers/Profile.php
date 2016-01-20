<?php

namespace App\Http\Controllers;

use App\Http\Helpers\OAuth as OAuthHelper;
use \Request;

class Profile extends Application
{
    /**
     * Show a list of all eaters
     */
    public function index()
    {
        $user = OAuthHelper::user();
        return view('profile/index', compact('user'));
    }

    /**
     * Overwrite the handicap of a user
     */
    public function setHandicap()
    {
        $user = OAuthHelper::user();
        $user->handicap = Request::get('handicap');

        if ($user->save()) {
            return response()->json(null, 200);
        }
        else {
            return response()->json([
                'error' => 'handicap_update_failed',
                'error_details' => 'Je dieetwensen konden niet worden opgeslagen'
            ], 500);
        }

    }
}
