<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Flash;
use App\Models\Meal;
use App;
use Illuminate\Http\Request;
use App\Services\UpdateMealService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateMeal extends Application
{
    /**
     * Shows the page for editing a new meal
     */
    public function edit($id)
    {
        $meal = Meal::find($id);
        if (!$meal) {
            \App::abort(404, "Maaltijd niet gevonden");
        }

        return view('meal/edit', ['meal' => $meal]);
    }

    /**
     * Processes the edit meal form to update a meal
     * @return Redirect
     */
    public function update($id, Request $request)
    {
        try {
            $meal = Meal::findOrFail($id);
        }
        catch(ModelNotFoundException $e) {
            return $this->userFriendlyError(404, 'Maaltijd bestaat niet');
        }

        try {
            $meal = with(new UpdateMealService($meal, $request->all()))->execute();
        }
        catch (ValidationException $e) {
            return redirect(action('UpdateMeal@edit', $meal->id))->withErrors($e->messages())->withInput();
        }

        if (! $meal) {
            return $this->userFriendlyError(500, 'Maaltijd kon niet worden geupdate; onbekende fout.');
        }

        // Update user
        Flash::set(Flash::SUCCESS, 'Maaltijd geupdate');
        return redirect(action('ShowMeal@show', $meal->id));
    }
}
