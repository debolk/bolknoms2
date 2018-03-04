<?php

namespace App\Http\Controllers\Administration;

use App;
use App\Http\Controllers\Application;
use App\Models\Meal;
use App\Services\UpdateMealService;
use App\Services\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

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

        return view('administration/meal/edit', ['meal' => $meal]);
    }

    /**
     * Processes the edit meal form to update a meal
     * @return Redirect
     */
    public function update($id, Request $request)
    {
        try {
            $meal = Meal::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return $this->userFriendlyError(404, 'Maaltijd bestaat niet');
        }

        // Proces input
        $data = $request->all();
        if (empty($data['event'])) {
            $data['event'] = null;
        }

        try {
            $meal = with(new UpdateMealService($meal, $data))->execute();
        } catch (ValidationException $e) {
            return redirect(action('Administration\UpdateMeal@edit', $meal->id))->withErrors($e->messages())->withInput();
        }

        if (!$meal) {
            return $this->userFriendlyError(500, 'Maaltijd kon niet worden geupdate; onbekende fout.');
        }

        // Update user
        return redirect(action('Administration\ShowMeal@show', $meal->id))
                ->with('action_result', ['status' => 'success', 'message' => "Maaltijd bijgewerkt"]);
    }
}
