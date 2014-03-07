<?php

class DashboardController extends ApplicationController
{
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
     * Creates a registration
     * @return View or a string "error" upon failure
     */
    public function aanmelden()
    {
        $meal = Meal::find((int)Input::get('meal_id'));
        if (!$meal) {
            App::abort(404, 'Maaltijd niet gevonden');
        }

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
}
