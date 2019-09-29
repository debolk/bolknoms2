<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Profile extends Application
{
    /**
     * Show a list of all eaters
     */
    public function index() : View
    {
        $user = $this->oauth->user();
        return view('profile/index', compact('user'));
    }

    /**
     * Overwrite the handicap of a user
     */
    public function setHandicap(Request $request) : JsonResponse
    {
        $user = $this->oauth->user();
        if (!$user) {
            return response()->json([
                'error' => 'handicap_update_failed',
                'error_details' => 'Gebruiker bestaat niet',
            ], 500);
        }

        $user->handicap = $request->get('handicap');

        if ($user->save()) {
            Log::info("User changed diet", ['user' => $user->id, 'handicap' => $user->handicap]);
            return response()->json([], 200);
        } else {
            return response()->json([
                'error' => 'handicap_update_failed',
                'error_details' => 'Je dieetwensen konden niet worden opgeslagen',
            ], 500);
        }
    }
}
