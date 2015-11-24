<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Application;
use App\Http\Helpers\Flash;
use App\Models\Meal;
use App\Services\DestroyMealService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Meals extends Application
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

        return view('administration/meals/index', [
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
        try {
            $meal = Meal::findOrFail($id);
        }
        catch (ModelNotFoundException $e) {
            return $this->userFriendlyError(404, 'Maaltijd bestaat niet');
        }

        $date = (string) $meal;
        $destroy = with(new DestroyMealService($meal))->execute();

        if (!$destroy) {
            return $this->userFriendlyError(500, 'Maaltijd kon niet worden verwijderd; onbekende fout.');
        }

        // Update user
        Flash::set(Flash::SUCCESS, "Maaltijd op $date verwijderd. Alle aanmeldingen zijn gemaild met een bevestiging.");
        return redirect(action('Administration\Meals@index'));
    }
}
