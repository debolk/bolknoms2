<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class Messenger extends Application
{
    /**
     * Provides the verification answer for the Facebook webhook setup
     * @param  Illuminate\Http\Request $request
     * @return string
     */
    public function verification(Request $request)
    {
        $verify_token = $request->get('hub_verify_token');
        $challenge = $request->get('hub_challenge');

        if (!$verify_token || !$challenge) {
            App::abort(400, 'no verify_token or no challenge sent');
        }

        if ($verify_token !== env('MESSENGER_VALIDATION_TOKEN')) {
            App::abort(400, 'wrong verification token');
        }

        return $challenge;
    }

    /**
     * Handle webhook subscriptions
     * @return string
     */
    public function webhook()
    {
        App:abort(501, 'This webhook is unimplemented');
    }
}
