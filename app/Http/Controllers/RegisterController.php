<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\Registration;
use Request;
use Validator;
use Log;

class RegisterController extends ApplicationController
{
    /**
      * Show the index that allows users to quickly register for the upcoming meal
      * @return View
      */
    public function index()
    {
        return $this->setPageContent(view('register/index', ['meals' => Meal::available()->get()]));
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

        if (!$meal->open_for_registrations()) {
            return response()->json([
                'error' => 'meal_deadline_expired',
                'error_details' => 'De aanmeldingsdeadline is verstreken'
            ], 400);
        }

        // Validate form
        $validator = Validator::make(Request::input(), [
            'name' => ['required', 'regex:/[A-Za-z -]+/', 'between:2,30'],
        ],[
            'name.required' => 'Je moet je naam invullen',
            'name.regex' => 'Je naam mag alleen (hoofd)letters, streepjes en spaties bevatten',
            'between' => 'Je naam moet minimaal twee en maximaal 30 tekens bevatten',
        ]);

        // Validate the user account matches our session
        if (Session::get('oauth.user_id') !== Input::get('user')) {
            return response()->json([
                'error' => 'session_user_not_matched',
                'error_details' => 'Je logingegevens voor de server zijn anders dan die van de client. Logout en probeer opnieuw.'
            ], 409);
        }

        if ($validator->passes()) {
            // Escape data
            $name = e(Request::input('name'));
            $handicap = e(Request::input('handicap'));

            $registration = new Registration(['name' => $name, 'handicap' => $handicap]);
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
        else {
            return response()->json([
                'error' => 'invalid_data',
                'error_details' => 'De data die je verstuurde is niet geldig'
            ], 406);
        }
    }
}
