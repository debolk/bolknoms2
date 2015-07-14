<?php

namespace App\Http\Controllers;
use App\Models\Meal;
use App\Http\Helpers\Flash;

class AdminDashboard extends Application
{
    /**
     * List all past and current meals
     * @return View
     */
    public function index()
    {
        $count = \Request::input('count', 5);
        if (!is_numeric($count)) {
            \App::abort(400, "Count parameter not an integer");
        }
        $upcoming_meals = Meal::upcoming();
        $previous_meals = Meal::previous();
        if ($count > 0) {
          $upcoming_meals->take($count);
          $previous_meals->take($count);
        }

        return $this->setPageContent(view('dashboard/index', [
            'upcoming_meals' => $upcoming_meals->get(),
            'previous_meals' => $previous_meals->get(),
        ]));
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
            \App::abort(404, "Maaltijd niet gevonden");
        }

        // Store the name of the meal for usage in the flash message
        $date = (string)$meal;

        // Send an e-mail to the registrations for confirmation
        \App\Http\Helpers\Mailer::mealIsDestroyedEmail($meal);

        // Remove all guests
        foreach ($meal->registrations()->get() as $registration) {
            $registration->delete();
        }

        // Remove the meal
        $meal->delete();

        // Update user
        Flash::set(Flash::SUCCESS,"Maaltijd op $date verwijderd. Alle aanmeldingen zijn gemaild met een bevestiging.");
        \Log::info("Maaltijd verwijderd: $date");
        return \Redirect::to('/administratie');
    }
}
