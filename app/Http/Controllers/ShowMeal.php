<?php

namespace App\Http\Controllers;

use App;
use App\Models\Meal;
use App\Models\Registration;
use App\Models\User;
use App\Services\DeregisterService;
use Log;
use Request;

class ShowMeal extends Application
{
    /**
     * Shows the details page of a meal
     */
    public function show($id)
    {
        $meal = Meal::find($id);
        if (!$meal) {
            App::abort(404, "Maaltijd niet gevonden");
        }

        return $this->setPageContent(view('meal/show', ['meal' => $meal, 'users' => User::orderBy('name')->get()]));
    }

    /**
     * Creates a registration
     * @return View or a string "error" upon failure
     */
    public function aanmelden()
    {
        if (Request::has('user_id')) {
            return $this->aanmelden_bolker();
        }
        else {
            return $this->aanmelden_anonmiem();
        }
    }

    public function aanmelden_bolker()
    {
        $meal = Meal::find((int)Request::input('meal_id'));
        if (!$meal) {
            return response()->json([
                'error' => 'meal_not_found',
                'error_details' => 'Maaltijd bestaat niet'
            ], 404);
        }

        $user = User::find(Request::input('user_id'));
        if (!$user) {
            return response()->json([
                'error' => 'user_not_found',
                'error_details' => 'Gebruiker bestaat niet'
            ], 404);
        }

        if ($user->blocked) {
            return response()->json([
                'error' => 'user_blocked',
                'error_details' => 'Deze gebruiker is geblokkeerd. Je kunt hem/haar niet aanmelden'
            ], 403);
        }

        // Create a new registration
        $registration = new Registration([
            'name' => $user->name,
            'handicap' => $user->handicap
        ]);
        $registration->confirmed = true;
        $registration->username = $user->username;
        $registration->email = $user->email;
        $registration->meal_id = $meal->id;
        $registration->user_id = $user->id;

        if ($registration->save()) {
            Log::info("Aangemeld: administratie|$registration->id|$registration->name");
            return view('meal/_registration', ['registration' => $registration]);
        }
        else {
            return response()->json([
                'error' => 'create_registration_admin_unknown_error',
                'error_details' => 'Deze registratie kon niet opgeslagen worden, reden onbekend.'
            ], 500);
        }
    }

    public function aanmelden_anonmiem()
    {
        $meal = Meal::find((int)Request::input('meal_id'));
        if (!$meal) {
            return response()->json([
                'error' => 'meal_not_found',
                'error_details' => 'Maaltijd bestaat niet'
            ], 404);
        }

        // Create a new registration
        $registration = new Registration([
            'name' => e(Request::input('name')),
            'handicap' => (Request::input('handicap') != '') ? e(Request::input('handicap')) : null,
        ]);
        $registration->confirmed = true;
        $registration->meal_id = $meal->id;

        if ($registration->save()) {
            Log::info("Aangemeld: administratie|$registration->id|$registration->name");
            return view('meal/_registration', ['registration' => $registration]);
        }
        else {
            return response()->json([
                'error' => 'create_registration_admin_unknown_error',
                'error_details' => 'Deze registratie kon niet opgeslagen worden, reden onbekend.'
            ], 500);
        }
    }

    /**
     * Removes a registration from a meal
     * @param int $id the id of the registration to remove
     * @return string "success" if succesfull
     */
    public function afmelden($id)
    {
        // Find registration
        $registration = Registration::find((int)$id);
        if (!$registration) {
            return response()->json([
                'error' => 'registration_not_existent',
                'error_details' => 'Deze registratie bestaat niet'
            ], 500);
        }

        // Deregister from the meal
        with(new DeregisterService($registration, true))->execute();
        return response(null, 204);
    }
}
