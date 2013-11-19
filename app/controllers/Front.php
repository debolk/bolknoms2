<?php

class Front extends ApplicationController
{
  public function index()
  {

  }

  public function inschrijven_specifiek($id)
  {
    $meal = Meal::find($id);
    $this->layout->content = View::make('front/inschrijven_specifiek', ['meal' => $meal]);
  }

  public function aanmelden_specifiek($id)
  {

    //FIXME implement method
    $meal = Meal::find($id);

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
    //FIXME implement method
  }
  
  public function aanmelden()
  {
    //FIXME implement method
  }

  public function uitgebreidaanmelden()
  {
    //FIXME implement method
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
