<?php

class Front extends ApplicationController
{
  public function index()
  {
    $this->layout->content = View::make('front/index', ['upcoming_meal' => Meal::available()->first()]);
  }

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

  public function inschrijven_specifiek($id)
  {
    $meal = Meal::find($id);
    $this->layout->content = View::make('front/inschrijven_specifiek', ['meal' => $meal]);
  }

  public function aanmelden_specifiek($id)
  {
    $meal = Meal::find($id);

    //FIXME Extra validation rules are missing
    $validator = Validator::make(Input::all(), [
        'name' => ['required', 'regex:/[A-Za-z -]+/'],
    ],[
        'name.required' => 'Je moet je naam invullen',
        'name.regex' => 'Je naam mag alleen (hoofd)letters, streepjes en spaties bevatten',
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
  
  public function uitgebreidinschrijven()
  {
    $this->layout->content = View::make('front/uitgebreidinschrijven', ['meals' => Meal::available()->get()]);
  }
  
  public function uitgebreidaanmelden()
  {
    //FIXME extra validations are missing (email, etc)
    $validator = Validator::make(Input::all(), [
        'name' => ['required', 'regex:/[A-Za-z -]+/'],
    ],[
        'name.required' => 'Je moet je naam invullen',
        'name.regex' => 'Je naam mag alleen (hoofd)letters, streepjes en spaties bevatten',
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
        //FIXME Does not (yet) send e-mail
        //MailerRegistration::send_confirmation($name, $email, $registrations);

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
  
  public function afmelden()
  {
    //FIXME implement method
  }
  
  
  public function disclaimer()
  {
    $this->layout->content = View::make('front/disclaimer');
  }
  
  public function privacy()
  {
    $this->layout->content = View::make('front/privacy');
  }

  private function valideer_aanmelding()
  {
    //FIXME implement method
  }
  
  private function valideer_uitgebreideaanmelding()
  {
    //FIXME implement method
  }

  public function errors()
  {
    //FIXME implement method
  }
}
