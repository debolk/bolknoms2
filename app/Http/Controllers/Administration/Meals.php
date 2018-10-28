<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Application;
use App\Models\Meal;
use App\Services\DestroyMealService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Meals extends Application
{
    /**
     * List all past and current meals
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $count = (int) \Request::input('count', '5');

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
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function verwijder($id)
    {
        try {
            $meal = Meal::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return $this->userFriendlyError(404, 'Maaltijd bestaat niet');
        }

        $date = (string) $meal;
        $destroy = with(new DestroyMealService($meal))->execute();

        if (!$destroy) {
            return $this->userFriendlyError(500, 'Maaltijd kon niet worden verwijderd; onbekende fout.');
        }

        // Update user
        return redirect(action('Administration\Meals@index'))
                ->with('action_result', [
                    'status' => 'success',
                    'message' => "Maaltijd op $date verwijderd. Alle aanmeldingen zijn gemaild met een bevestiging."
                ]);
    }
}
