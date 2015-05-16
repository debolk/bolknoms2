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
        if (OAuth::valid()) {
            $data['user'] = OAuth::user();
            $data['meals'] = Meal::available()->get();
        }
        else {
            $data['meals'] = Meal::available()->take(1)->get();
        }

        return $this->setPageContent(view('register/index', $data));
    }

    public function aanmelden()
    {
        // Choose between anonymous and user registration
        if (Request::has('name')) {
            return $this->aanmeldenAnoniem();
        }
        else {
            return $this->aanmeldenBolker();
        }
    }

    public function aanmeldenAnoniem()
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

        // Validate input data
        $validator = \Validator::make(Request::all(), [
            'email' => ['email', 'required'],
            'name' => ['required'],
        ],[
            'name.required' => 'Je moet je naam invullen',
            'email.required' => 'Je moet je e-mailadres invullen',
            'email.email' => 'Het ingevulde e-mailadres is ongeldig',
        ]);

        if (! $validator->passes()) {
            return response()->json([
                'error' => 'input_invalid',
                'error_details' => 'Naam of e-mailadres niet ingevuld of ongeldig',
            ], 400);
        }

        // Create registration
        $registration = new Registration([
            'name' => Request::get('name'),
            'handicap' => Request::get('handicap', null),
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

    /**
     * Subscribe a user to a single meal
     * @return json
     */
    public function aanmeldenBolker()
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

        // Check if the user is already registered
        $user = OAuth::user();
        if ($user->registeredFor($meal)) {
            return response()->json([
                'error'         => 'double_registration',
                'error_details' => 'Je bent al aangemeld voor deze maaltijd',
            ], 400);
        }

        // Create registration
        $registration = new Registration([
            'username' => $user->id,
            'name' => $user->name,
            'handicap' => $user->getHandicap(),
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

    /**
     * Unsubscribe a user from a meal
     * @return JSON
     */
    public function afmelden()
    {
        // Find the meal
        $meal = Meal::find((int) Request::input('meal_id'));
        if (!$meal) {
            return response()->json([
                'error' => 'meal_not_found',
                'error_details' => 'De maaltijd bestaat niet'
            ], 404);
        }

        // Check if the meal is still open
        if (!$meal->open_for_registrations()) {
            return response()->json([
                'error' => 'meal_deadline_expired',
                'error_details' => 'De aanmeldingsdeadline is verstreken'
            ], 400);
        }

        // Find the registration data
        $user = OAuth::user();
        $registration = $user->registrationFor($meal);
        if (!$registration) {
            return response()->json([
                'error' => 'no_registration',
                'error_details' => 'Je bent niet aangemeld voor deze maaltijd'
            ], 404);
        }

        // Destroy the registration
        $id = $registration->id;
        $name = $registration->name;
        $registration->delete();

        \Log::info("Afgemeld $registration->name (ID: $registration->id) voor $meal (ID: $meal->id) door $user->name (ID: $user->id)");
        return response(null, 200);
    }
}
