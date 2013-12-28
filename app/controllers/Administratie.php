<?php

class Administratie extends ApplicationController
{
    /**
     * Initializes the controller, forcing all users to authenticate before touching anything
     */
    public function __construct()
    {
        parent::__construct();

        // Authenticate users
        $this->authenticate();
    }

    /**
     * List all past and current meals
     * @return void
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

    public function nieuwe_maaltijd()
    {
        $this->layout->content = View::make('administratie/nieuwe_maaltijd', [
            'meal' => new Meal,
        ]);
    }

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
            $meal->locked = e(Input::get('locked'));
            $meal->event = e(Input::get('event'));
            $meal->promoted = e(Input::get('promoted'));

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
     * @return void
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

    // /**
    //  * Edits a meal
    //  * @throws HTTP_Exception_404
    //  * @return void
    //  */
    // public function action_bewerk()
    // {
    //     $this->template->content->meal = $meal = ORM::factory('meal',$this->request->param('id'));
    //     if (! $meal->loaded()) {
    //         throw new HTTP_Exception_404;
    //     }

    //     if ($_POST) {
    //         $_POST = Helper_Form::prep_form($_POST);
    //         $_POST['promoted'] = (isset($_POST['promoted'])) ? (1) : (0);
    //         $meal->values($_POST, array('date','locked', 'event', 'promoted'));
    //         try {
    //             $meal->save();
    //             Flash::set(Flash::SUCCESS, 'Maaltijd geüpdate');
    //             Log::instance()->add(Log::NOTICE, "Maaltijd veranderd: $meal->id|$meal->date");
    //             $this->redirect(Route::url('default',array('controller' => 'administratie')));
    //         }
    //         catch (ORM_Validation_Exception $e) {
    //             // Nothing here, errors are retrieved in the view
    //         }
    //     }
    // }


    // /**
    //  * Creates a registration
    //  * @return void
    //  */
    // public function action_aanmelden()
    // {
    //     // Build an array of the data to store
    //     $data = array(
    //         'meal_id' => (int)$_POST['meal_id'],
    //         'name' => (string)$_POST['name'],
    //         'handicap' => (string)$_POST['handicap']
    //     );
    //     // Find the meal we're changing
    //     $meal = ORM::factory('meal',$data['meal_id']);
    //     if (! $meal->loaded()) {
    //         throw new HTTP_Exception_404;
    //     }

    //     // Create a new registration
    //     $registration = ORM::factory('Registration')->values($data,array('meal_id','name','handicap'));
    //     try {
    //         $registration->save();
    //         Log::instance()->add(Log::NOTICE, "Aangemeld: administratie|$registration->id|$registration->name");
    //         echo View::factory('administratie/_meal',array('meal' => $meal));
    //     }
    //     catch (ORM_Validation_Exception $e) {
    //         echo 'error';
    //     }
    //     //FIXME Manual override of template engine
    //     exit;
    // }

    // /**
    //  * Removes a registration
    //  * @return void
    //  */
    // public function action_afmelden()
    // {
    //     $registration = ORM::factory('registration',$this->request->param('id'));
    //     $id = $registration->id;
    //     $name = $registration->name;
    //     $meal = $registration->meal;

    //     $registration->delete();
    //     Log::instance()->add(Log::NOTICE, "Afgemeld: administratie|$id|$name|$meal");

    //     if ($this->request->is_ajax()) {
    //         echo 'success';
    //         exit;
    //     }
    //     else {
    //         Flash::set(Flash::SUCCESS,"$name afgemeld voor de maaltijd op $meal");
    //         $this->redirect('/administratie');
    //     }
    // }

    /**
     * Prints an array (json-encoded) of all upcoming dates with meals planned
     * used for the date-picker to hide all dates already filled
     * @return void
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
    
    // /**
    //  * Prints a checklist for crossing off visiting users
    //  * not intended to be viewed, only printed
    //  */
    // public function action_checklist()
    // {
    //     $meal_id = $this->request->param('id');
    //     $meal = ORM::factory('meal',$meal_id);
    //     if (!$meal->loaded()) {
    //         throw new HTTP_Exception_404("Maaltijd niet gevonden");
    //     }
    //     echo View::factory('administratie/checklist',array('meal' => $meal));
    //     exit;
    // }
}