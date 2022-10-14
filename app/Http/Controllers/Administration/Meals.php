<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Services\DestroyMealService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class Meals extends Controller
{
    /**
     * List all past and current meals
     */
    public function index(Request $request): View
    {
        $count = (int) $request->get('count', 5);

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
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function verwijder(int $id)
    {
        $meal = Meal::where('id', $id)->first();
        if (! $meal) {
            return $this->userFriendlyError(404, 'Maaltijd bestaat niet');
        }

        $date = (string) $meal;
        $destroy = (new DestroyMealService($meal))->execute();

        if (! $destroy) {
            return $this->userFriendlyError(500, 'Maaltijd kon niet worden verwijderd; onbekende fout.');
        }

        // Update user
        return redirect(action([self::class, 'index']))
                ->with('action_result', [
                    'status' => 'success',
                    'message' => "Maaltijd op $date verwijderd. Alle aanmeldingen zijn gemaild met een bevestiging.",
                ]);
    }
}
