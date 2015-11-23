<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Flash;
use App\Models\Meal;
use Illuminate\Http\Request;
use App\Services\CreateMealService;
use App\Services\ValidationException;

class CreateMeal extends Application
{
    /**
     * Shows the page for creating a new meal
     */
    public function index()
    {
        return view('meal/nieuwe_maaltijd', ['meal' => new Meal]);
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
            $data['meal_timestamp'] = date('d-m-Y').' 18:30';
        }
        if (empty($data['locked_timestamp'])) {
            $data['locked_timestamp'] = date('d-m-Y').' 15:00';
        }

        // Create the meal
        try {
            $meal = with(new CreateMealService($data))->execute();
        }
        catch (ValidationException $e) {
            return redirect('/administratie/nieuwe_maaltijd')->withErrors($e->messages())->withInput();
        }

        if ($meal) {
            Flash::set(Flash::SUCCESS, 'Maaltijd toegevoegd op ' . $meal);
            return redirect('/administratie');
        }
        else {
            return $this->userFriendlyError(500, 'Maaltijd kon niet worden aangemaakt: onbekende fout');
        }
    }
}
