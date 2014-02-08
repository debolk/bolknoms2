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
            'name' => ['required', 'regex:/[A-Za-z -]+/'],
        ],[
            'name.required' => 'Je moet je naam invullen',
            'name.regex' => 'Je naam mag alleen (hoofd)letters, streepjes en spaties bevatten',
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
            'name' => ['required', 'regex:/[A-Za-z -]+/'],
            'email' => 'email',
            ],[
            'name.required' => 'Je moet je naam invullen',
            'name.regex' => 'Je naam mag alleen (hoofd)letters, streepjes en spaties bevatten',
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
            'name' => ['required', 'regex:/[A-Za-z -]+/'],
            'email' => 'email',
            'meals' => 'required',
            ],[
            'name.required' => 'Je moet je naam invullen',
            'name.regex' => 'Je naam mag alleen (hoofd)letters, streepjes en spaties bevatten',
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
            // Update user
            MailerRegistration::send_confirmation($name, $email, $registrations);

            // Determine success text
            if (trim($email)) {
                $text = 'Aanmelding geslaagd. Je ontvangt een e-mail met alle details.';
            }
            else {
                $text = 'Aanmelding geslaagd. ';
            }
            Flash::set(Flash::SUCCESS, "<p>$text</p>".Chef::random_video());
        }
        else {
            Session::flash('validation_errors', $validator->messages());
        }
        return Redirect::to('/uitgebreid-inschrijven');
    }
  
    /**
    * Removes a registration for a meal
    * @return Redirect
    */
    public function afmelden($id, $salt)
    {
        // Find the registration
        $registration = Registration::find($id);
        if (!$registration) {
            Flash::set(Flash::ERROR, 'We kunnen je niet afmelden voor deze maaltijd, want je bent niet aangemeld.  Misschien ben je eerder al afgemeld.');
            return Redirect::to('/');
        }

        // Check if the salt is valid
        if ($registration->salt !== $salt) {
            Flash::set(Flash::ERROR, 'De beveiligingscode klopt niet. Je bent niet afgemeld.');
            return Redirect::to('/');
        }

        // Check if the subscription period has not ended yet
        if (! $registration->meal->open_for_registrations()) {
            Flash::set(Flash::ERROR, 'De inschrijving voor deze maaltijd is gesloten. Je kunt je niet meer afmelden.');
            return Redirect::to('/');
        }

        // Store variables for later usage
        $date = (string)$registration->meal;
        $id   = $registration->id;
        $name = $registration->name;
        $meal = $registration->meal->date;

        // Remove registration
        $registration->delete();
        Log::info("Afgemeld: e-mail|$id|$name|$meal");

        // Notify the user
        Flash::set(Flash::SUCCESS, "Je bent afgemeld voor de maaltijd op $date");
        return Redirect::to('/');
    }

    /**
     * Displays the disclaimer page
     * @return View
     */
    public function disclaimer()
    {
        $this->layout->content = View::make('front/disclaimer');
    }

    /**
     * Displays the privacy statement
     * @return View
     */
    public function privacy()
    {
        $this->layout->content = View::make('front/privacy');
    }
}
