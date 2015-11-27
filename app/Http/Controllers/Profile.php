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
        $user = OAuth::user();

        // Calculate the rank of the userr
        $rank = $user->topEatersPositionThisYear();
        $count = $user->numberOfRegistrationsThisYear();

        // Determine the colour of the medal to display
        $medal = '';
        if ($rank === null) {
            $medal = 'gray';
        }
        elseif ($rank <= 10) {
            $medal = 'gold';
        }
        elseif ($rank <= 20) {
            $medal = 'silver';
        }

        return view('profile/index', compact('user', 'rank', 'medal', 'count'));
    }

    /**
     * Overwrite the handicap of a user
     */
    public function setHandicap()
    {
        $user = OAuth::user();
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
