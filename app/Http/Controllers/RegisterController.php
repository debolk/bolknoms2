<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\Registration;
use Request;
use Validator;
use Log;
use App\Http\Helpers\OAuth;

class RegisterController extends ApplicationController
{
    /**
      * Show the index that allows users to quickly register for the upcoming meal
      * @return View
      */
    public function index()
    {
        $data = [];

        // Add more data if we have a current user
        if (OAUth::valid()) {
            $data['user'] = OAuth::user();
            $data['meals'] = Meal::available()->get();
        }
        else {
            $data['meals'] = Meal::available()->take(1)->get();
        }

        return $this->setPageContent(view('register/index', $data));
    }

    /**
     * Subscribe a user to a single meal
     * @return Redirect
     */
    public function aanmelden()
    {
        // Find the meal
        $meal = Meal::find((int) Request::input('meal_id'));
        if (!$meal) {
            return response()->json([
                'error' => 'meal_not_found',
                'error_details' => 'De maaltijd waarvoor je je probeert aan te melden bestaat niet'
            ], 404);
        }

        // Check if the meal is still open
        if (!$meal->open_for_registrations()) {
            return response()->json([
                'error' => 'meal_deadline_expired',
                'error_details' => 'De aanmeldingsdeadline is verstreken'
            ], 400);
        }

        // Create registration
        $registration = new Registration([
            'username' => OAuth::user()->id,
            'name' => OAuth::user()->name,
        ]);
        $registration->meal_id = $meal->id;

        if ($registration->save()) {
            \Log::info("Aangemeld: $registration->id|$registration->name");
            return response(null, 200);
        }
        else {
            \Log::error("Aanmelding mislukt, onbekend");
            return response()->json([
                'error' => 'unknown',
                'error_details' => 'unknown_internal_server_error'
            ], 500);
        }
    }
}
