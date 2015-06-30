<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\Registration;
use Log;
use App;
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

        return $this->setPageContent(view('meal/show', ['meal' => $meal]));
    }

    /**
     * Creates a registration
     * @return View or a string "error" upon failure
     */
    public function aanmelden()
    {
        $meal = Meal::find((int)Request::input('meal_id'));
        if (!$meal) {
            App::abort(404, 'Maaltijd niet gevonden');
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

        // Store data for later usage
        $id = $registration->id;
        $name = $registration->name;
        $meal = $registration->meal;

        if ($registration->delete()) {
            Log::info("Afgemeld: administratie|$id|$name|$meal");
            return response(null, 200);
        }
        else {
            return response()->json([
                'error' => 'destroy_registration_admin_unknown_error',
                'error_details' => 'Deze registratie kon niet verwijderd worden, reden onbekend.'
            ], 500);
        }
    }
}
