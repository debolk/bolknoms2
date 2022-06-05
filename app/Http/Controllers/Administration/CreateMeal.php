<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Services\CreateMealService;
use App\Services\ValidationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CreateMeal extends Controller
{
    /**
     * Shows the page for creating a new meal
     */
    public function index(): View
    {
        return view('administration/meal/nieuwe_maaltijd', ['meal' => new Meal()]);
    }

    /**
     * Processes the new meal form to create a new meal
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Construct candidate object
        $data = $request->all();

        // Use todays date as defaults if none are given
        if (empty($data['meal_timestamp'])) {
            $data['meal_timestamp'] = date('d-m-Y').' 18:30';
        }
        if (empty($data['locked_timestamp'])) {
            $data['locked_timestamp'] = date('d-m-Y').' 15:00';
        }

        // Set the event null if none is passed
        if (empty($data['event'])) {
            $data['event'] = null;
        }

        // Create the meal
        try {
            $meal = with(new CreateMealService($data))->execute();
        } catch (ValidationException $e) {
            return redirect(action([\App\Http\Controllers\Administration\CreateMeal::class, 'index']))->withErrors($e->messages())->withInput();
        }

        if ($meal) {
            return redirect(action([\App\Http\Controllers\Administration\Meals::class, 'index']))
                    ->with('action_result', ['status' => 'success', 'message' => 'Maaltijd toegevoegd op '.$meal]);
        } else {
            return $this->userFriendlyError(500, 'Maaltijd kon niet worden aangemaakt: onbekende fout');
        }
    }
}
