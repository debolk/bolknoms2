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
        return new Illuminate\Http\Response(json_encode(['error' => 'not_authorized', 'error_details' => 'random fluke was not okay']), 406);

        $meal = Meal::available()->first();

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

            if ($meal) {
                $registration = new Registration(['name' => $name]);
                $registration->meal_id = $meal->id;

                if ($registration->save()) {
                    Log::info("Aangemeld: snel|$registration->id|$registration->name");
                    Flash::set(Flash::SUCCESS, '<p>Aanmelding geslaagd. Je kunt mee-eten.</p>'.Chef::random_video());
                }
                else {
                    Flash::set(Flash::ERROR, "Je aanmelding is mislukt. Probeer het nogmaals.");
                }
            }
            else {
                App::abort(404, 'Maaltijd niet gevonden');
            }
        }
        else {
            Session::flash('validation_errors', $validator->messages());
            // Repopulate the form
            Input::flash();
        }
        return Redirect::to('/');
    }
}
