<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Application;
use App\Http\Helpers\Flash;
use App\Models\Meal;
use App\Services\CreateMealService;
use App\Services\ValidationException;
use Illuminate\Http\Request;

class CreateMeal extends Application
{
    /**
     * Shows the page for creating a new meal
     */
    public function index()
    {
        return view('administration/meal/nieuwe_maaltijd', ['meal' => new Meal]);
    }

    /**
     * Processes the new meal form to create a new meal
     * @return Redirect
     */
    public function create(Request $request)
    {
        // Construct candidate object
        $data = $request->all();

        // Use todays date as defaults if none are given
        if (empty($data['meal_timestamp'])) {
            $data['meal_timestamp'] = date('d-m-Y') . ' 18:30';
        }
        if (empty($data['locked_timestamp'])) {
            $data['locked_timestamp'] = date('d-m-Y') . ' 15:00';
        }

        // Create the meal
        try {
            $meal = with(new CreateMealService($data))->execute();
        }
        catch (ValidationException $e) {
            return redirect(action('Administration\CreateMeal@index'))->withErrors($e->messages())->withInput();
        }

        if ($meal) {
            Flash::set(Flash::SUCCESS, 'Maaltijd toegevoegd op ' . $meal);
            return redirect(action('Administration\Meals@index'));
        }
        else {
            return $this->userFriendlyError(500, 'Maaltijd kon niet worden aangemaakt: onbekende fout');
        }
    }
}