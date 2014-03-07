<?php

class Front extends ApplicationController
{
    /**
      * Show the index that allows users to quickly register for the upcoming meal
      * @return View
      */ 
    public function index()
    {
        $this->layout->content = View::make('front/index', ['upcoming_meal' => Meal::available()->first()]);
    }

    /**
     * Subscribe a user to a single meal
     * @return Redirect
     */
    public function aanmelden()
    {
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

    /**
     * Show the interface to register for a specific meal
     * @param int $id the meal to register for
     * @return View
     */
    public function inschrijven_specifiek($id)
    {
        $meal = Meal::find($id);
        $this->layout->content = View::make('front/inschrijven_specifiek', ['meal' => $meal]);
    }

    /**
     * Register for a specific meal
     * @param int $id the meal to register for
     * @return Redirect
     */
    public function aanmelden_specifiek($id)
    {
        $meal = Meal::find($id);

        $validator = Validator::make(Input::all(), [
            'name' => ['required', 'regex:/[A-Za-z -]+/', 'between:2,30'],
            'email' => 'email',
            ],[
            'name.required' => 'Je moet je naam invullen',
            'name.regex' => 'Je naam mag alleen (hoofd)letters, streepjes en spaties bevatten',
            'between' => 'Je naam moet minimaal twee en maximaal 30 tekens bevatten',
            'email.email' => 'Het ingevulde e-mailadres is niet geldig',
        ]);

        if ($validator->passes()) {
            // Escape data
            $name = e(Input::get('name'));
            $handicap = e(Input::get('handicap'));
            $email = e(Input::get('email'));

            if ($meal) {
                $registration = new Registration();
                $registration->name = $name;
                $registration->handicap = $handicap;
                $registration->email = $email;
                $registration->meal_id = $meal->id;

                if ($registration->save()) {
                    Log::info("Aangemeld: specifiek|$registration->id|$registration->name");
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
        return Redirect::route('inschrijven_specifiek', ['id' => $meal->id]);
    }
  
    /**
     * Show the interface to register for many meals in one fell swoop
     * @return View
     */
    public function uitgebreidinschrijven()
    {
        $this->layout->content = View::make('front/uitgebreidinschrijven', ['meals' => Meal::available()->get()]);
    }
  
    /**
     * Registers for many meals in one swoop
     * @return Redirect
     */
    public function uitgebreidaanmelden()
    {
        $validator = Validator::make(Input::all(), [
            'name' => ['required', 'regex:/[A-Za-z -]+/', 'between:2,30'],
            'email' => 'email',
            'meals' => 'required',
            ],[
            'name.required' => 'Je moet je naam invullen',
            'name.regex' => 'Je naam mag alleen (hoofd)letters, streepjes en spaties bevatten',
            'between' => 'Je naam moet minimaal twee en maximaal 30 tekens bevatten',
            'email.email' => 'Het ingevulde e-mailadres is niet geldig',
            'meals.required' => 'Je moet minstens &eacute;&eacute;n maaltijd aanvinken',
        ]);

        if ($validator->passes()) {
            // Escape data
            $name = e(Input::get('name'));
            $handicap = e(Input::get('handicap'));
            $email = e(Input::get('email'));

            // Create registrations
            $registrations = array();
            foreach (Input::get('meals') as $meal_id) {
                $registration = new Registration();
                $registration->name = $name;
                $registration->email = $email;
                $registration->handicap = $handicap;
                $registration->meal_id = (int)$meal_id;
                $registration->save();
                Log::info("Aangemeld: uitgebreid|$registration->id|$registration->name");
                $registrations[] = $registration;
            }

            // Send e-mail if needed
            $text = '';
            if (trim($email)) {
                // Send e-mail
                MailerRegistration::send_confirmation($name, $email, $registrations);

                // Change success message
                $text = 'Aanmelding geslaagd. Je ontvangt een e-mail met alle details.';
            }
            Flash::set(Flash::SUCCESS, "<p>Aanmelding geslaagd. $text</p>".Chef::random_video());
        }
        else {
            Session::flash('validation_errors', $validator->messages());
            // Repopulate the form
            Input::flash();

        }
        return Redirect::to('/uitgebreid-inschrijven');
    }
}
