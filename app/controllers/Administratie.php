<?php

class Administratie extends ApplicationController
{
    /**
     * Initializes the controller, forcing all users to authenticate before touching anything
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        // Authenticate users
        $this->authenticate();
    }

    /**
     * List all past and current meals
     * @return View
     */
    public function index()
    {
        $count = Input::get('count', 5);
        if (!is_numeric($count)) {
            App::abort(400, "Count parameter not an integer");
        }
        $upcoming_meals = Meal::upcoming();
        $previous_meals = Meal::previous();
        if ($count > 0) {
          $upcoming_meals->take($count);
          $previous_meals->take($count);
        }
        $this->layout->content = View::make('administratie/index', [
            'upcoming_meals' => $upcoming_meals->get(),
            'previous_meals' => $previous_meals->get(),
        ]);
    }

    /**
     * Shows the page for creating a new meal
     * @return View
     */
    public function nieuwe_maaltijd()
    {
        $this->layout->content = View::make('administratie/nieuwe_maaltijd', [
            'meal' => new Meal,
        ]);
    }

    /**
     * Processes the new meal form to create a new meal
     * @return Redirect
     */
    public function nieuwe_maaltijd_maken()
    {
        $meal = new Meal;

        $validator = Validator::make(Input::all(), [
            'date' => ['required', 'date'], //FIXME validate that the day is after today; and not taken
            'locked' => ['required', 'regex:/^[0-2][0-9]:[0-5][0-9]$/'],
            'event' => ['alpha'],
            'promoted' => ['in:0,1'],
        ]);

        if ($validator->passes()) {
            // Set data
            $meal->date = e(Input::get('date'));
            $meal->locked = e(Input::get('locked', '15:00'));
            $meal->event = (Input::get('event')) ? (e(Input::get('event'))) : null;
            $meal->promoted = Input::get('promoted', false);

            if ($meal->save()) {
                Log::info("Nieuwe maaltijd: $meal->id|$meal->date");
                Flash::set(Flash::SUCCESS, 'Maaltijd toegevoegd op '.$meal);
                return Redirect::to('/administratie');
            }
            else {
                Flash::set(Flash::ERROR, 'Maaltijd kon niet worden toegevoegd');
            }
        }
        else {
            Session::flash('validation_errors', $validator->messages());
        }
        return Redirect::to('/administratie/nieuwe_maaltijd');
    }

    /**
     * Removes a meal
     * @param int $id the id of the meal to remove
     * @return Redirect
     */
    public function verwijder($id)
    {
        // Find the meal
        $meal = Meal::find($id);
        if (!$meal) {
            App::abort(404, "Maaltijd niet gevonden");
        }

        // Store the name of the meal for usage in the flash message
        $date = (string)$meal;

        // Remove all guests
        foreach ($meal->registrations()->get() as $registration) {
            $registration->delete();
        }

        // Remove the meal
        $meal->delete();

        // Update user
        Flash::set(Flash::SUCCESS,"Maaltijd op $date verwijderd");
        Log::info("Maaltijd verwijderd: $date");
        return Redirect::to('/administratie');
    }

    /**
     * Shows the form to edit an existing meal
     * @param int $id the id of the meal to edit
     * @return View
     */
    public function bewerk($id)
    {
        $meal = Meal::find($id);

        if (!$meal) {
            App::abort(404, "Maaltijd niet gevonden");
        }

        $this->layout->content = View::make('administratie/bewerk', ['meal' => $meal]);
    }

    /**
     * Processes the form to edit an existing meal
     * @param int $id the id of the meal to update
     * @return Redirect
     */
    public function update($id)
    {
        $meal = Meal::find($id);
        if (!$meal) {
            App::abort(404, 'Maaltijd niet gevonden');
        }

        $validator = Validator::make(Input::all(), [
            'date' => ['required', 'date'], //FIXME validate that the day is after today; and not taken
            'locked' => ['required', 'regex:/^[0-2][0-9]:[0-5][0-9]$/'],
            'event' => ['alpha'],
            'promoted' => ['in:0,1'],
        ]);

        if ($validator->passes()) {
            $data = [
                'date' => e(Input::get('date')),
                'locked' => e(Input::get('locked')),
                'event' => e(Input::get('event')),
                'promoted' => Input::get('promoted', false)
            ];
            if ($meal->update($data)) {
                Flash::set(Flash::SUCCESS,"Maaltijd ge&uuml;pdate");
                Log::info("Maaltijd veranderd: $meal->id|$meal->date");
                return Redirect::to('/administratie');
            }
            else {
                Flash::set(Flash::ERROR, 'Maaltijd kon niet worden bewerkt');
            }
        }
        else {
            Session::flash('validation_errors', $validator->messages());
        }
        return Redirect::to('/administratie/bewerk/'.$meal->id);
    }

    /**
     * Creates a registration
     * @return View or a string "error" upon failure
     */
    public function aanmelden()
    {
        $meal = Meal::find((int)Input::get('meal_id'));
        if (!$meal) {
            App::abort(404, 'Maaltijd niet gevonden');
        }

        //FIXME validate data

        // Create a new registration
        $registration = new Registration([
            'name' => e(Input::get('name')),
            'handicap' => (Input::get('handicap') != '') ? e(Input::get('handicap')) : null,
        ]);
        $registration->meal_id = $meal->id;

        if ($registration->save()) {
            Log::info("Aangemeld: administratie|$registration->id|$registration->name");
            return View::make('administratie/_meal', ['meal' => $meal]);
        }
        else {
            return 'error';
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
            App::abort(404, 'Registratie bestaat niet');
        }

        // Store data for later usage
        $id = $registration->id;
        $name = $registration->name;
        $meal = $registration->meal;

        $registration->delete();
        Log::info("Afgemeld: administratie|$id|$name|$meal");
        return 'success';
    }

    /**
     * Prints an array (json-encoded) of all upcoming dates with meals planned
     * used for the date-picker to hide all dates already filled
     * @return string
     */
    public function gevulde_dagen()
    {
        $id = Input::get('meal_id', null);

        $meals = Meal::upcoming()->get();
        $dates = array();
        foreach ($meals as $meal) {
            if ($id !== $meal->id) {
                $dates[] = $meal->date;
            }
        }
        header('Content-Type: application/json');
        return json_encode($dates);
    }
    
    /**
     * Prints a checklist for crossing off visiting users
     * not intended to be viewed, only printed
     * @param int $id the id of the meal for which registrations are requested
     * @return View
     */
    public function checklist($id)
    {
        $meal = Meal::find($id);
        if (!$meal) {
            App::abort(404, 'Maaltijd niet gevonden');
        }

        return View::make('administratie/checklist',['meal' => $meal]);
    }
}