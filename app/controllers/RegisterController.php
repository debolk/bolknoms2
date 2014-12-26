<?php

class RegisterController extends ApplicationController
{
    /**
      * Show the index that allows users to quickly register for the upcoming meal
      * @return View
      */
    public function index()
    {
        $this->layout->content = View::make('register/index', ['meals' => Meal::available()->get()]);
    }

    /**
     * Subscribe a user to a single meal
     * @return Redirect
     */
    public function aanmelden()
    {
        // Find the meal
        $meal = Meal::find((int) Input::get('meal_id'));
        if (!$meal) {
            return Response::json(['error' => 'meal_not_found', 'error_details' => 'De maaltijd waarvoor je je probeert aan te melden bestaat niet'], 404);
        }

        // Validate form
        $validator = Validator::make(Input::all(), [
            'name' => ['required', 'regex:/[A-Za-z -]+/', 'between:2,30'],
        ],[
            'name.required' => 'Je moet je naam invullen',
            'name.regex' => 'Je naam mag alleen (hoofd)letters, streepjes en spaties bevatten',
            'between' => 'Je naam moet minimaal twee en maximaal 30 tekens bevatten',
        ]);

        if ($validator->passes()) {
            // Escape data
            $name = e(Input::get('name'));
            $handicap = e(Input::get('handicap'));

            $registration = new Registration(['name' => $name, 'handicap' => $handicap]);
            $registration->meal_id = $meal->id;

            if ($registration->save()) {
                Log::info("Aangemeld: $registration->id|$registration->name");
                return Response::json([], 200);
            }
            else {
                Log::error("Aanmelding mislukt, onbekend");
                return Response::json(['error' => 'unknown', 'error_details' => 'unknown_internal_server_error'], 500);
            }
        }
        else {
            return Response::json(['error' => 'invalid_data', 'error_details' => 'De data die je verstuurde is niet geldig'], 406);
        }
    }
}
