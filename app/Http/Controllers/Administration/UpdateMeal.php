<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Services\UpdateMealService;
use App\Services\ValidationException;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class UpdateMeal extends Controller
{
    /**
     * Shows the page for editing a new meal
     */
    public function edit(int $id): View
    {
        $meal = Meal::find($id);
        if (! $meal) {
            abort(404, 'Maaltijd niet gevonden');
        }

        return view('administration/meal/edit', ['meal' => $meal]);
    }

    /**
     * Processes the edit meal form to update a meal
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function update(int $id, Request $request)
    {
        $meal = Meal::where('id', $id)->first();
        if (! $meal) {
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
            return redirect(action([self::class, 'edit'], $meal->id))->withErrors($e->messages())->withInput();
        }

        if (! $meal) {
            return $this->userFriendlyError(500, 'Maaltijd kon niet worden geupdate; onbekende fout.');
        }

        // Update user
        return redirect(action([\App\Http\Controllers\Administration\ShowMeal::class, 'show'], $meal->id))
                ->with('action_result', ['status' => 'success', 'message' => 'Maaltijd bijgewerkt']);
    }
}
