<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Flash;
use App\Models\Meal;
use Illuminate\Http\Request;
use Input;
use Session;
use App\Services\CreateMealService;
use App\Services\ValidationException;

class CreateMeal extends Application
{
    /**
     * Shows the page for creating a new meal
     */
    public function new_meal()
    {
        return $this->setPageContent(view('meal/nieuwe_maaltijd', ['meal' => new Meal]));
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
            Input::flash();
            Session::flash('validation_errors', $e->messages());
            return redirect('/administratie/nieuwe_maaltijd');
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
