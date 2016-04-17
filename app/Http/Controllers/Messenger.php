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
        $data = $request->get('hub');
        if (!$data || !isset($data['verify_token']) || !isset($data['challenge'])) {
            App::abort(400, 'Illegal input: no hub, or no verify_token or no challenge');
        }

        if ($data['verify_token'] !== env('MESSENGER_VALIDATION_TOKEN')) {
            App::abort(400, 'Error, wrong validation token');
        }

        return $data['challenge'];
    }
}
