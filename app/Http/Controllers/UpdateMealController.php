<?php

namespace App\Http\Controllers;

use \App\Http\Helpers\Flash;
use \App\Models\Meal;

class UpdateMealController extends ApplicationController
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

        return $this->setPageContent(view('meal/edit', ['meal' => $meal]));
    }

    /**
     * Processes the edit meal form to update a meal
     * @return Redirect
     */
    public function update($id)
    {
        // Only update existing meals
        $meal = Meal::find($id);
        if (!$meal) {
            \App::abort(404, "Maaltijd niet gevonden");
        }

        // Format Dutch date to DB date (dd-mm-yyyy -> yyyy-mm-dd)
        $meal_data = \Request::all();
        $date = \DateTime::createFromFormat('d-m-Y', $meal_data['date']);
        $meal_data['date'] = ($date) ? ($date->format('Y-m-d')) : (null);

        // Validate the resulting input
        $validator = \Validator::make($meal_data, [
            'date' => ['date', 'required', 'unique:meals,date,'.$meal->id],
            'locked' => ['regex:/^[0-2][0-9]:[0-5][0-9]$/'],
        ],[
            'date.required' => 'De ingevulde datum is ongeldig',
            'date.date' => 'De ingevulde datum is ongeldig',
            'date.unique' => 'Op de ingevulde datum is al een maaltijd gepland',
            'locked.regex' => 'De sluitingstijd moet als HH:MM ingevuld zijn',
        ]
        );

        if ($validator->passes()) {
            // Save new meal
            $meal->update($meal_data);
            if ($meal->save()) {
                \Log::info("Maaltijd geupdate: $meal->id|$meal->date|$meal->event");
                Flash::set(Flash::SUCCESS, 'Maaltijd geupdate'.$meal);
                return redirect('/administratie/' . $meal->id);
            }
            else {
                Flash::set(Flash::ERROR, 'Maaltijd kon niet worden geupdate');
            }
        }
        else {
            Session::flash('validation_errors', $validator->messages());
            return redirect('/administratie/' . $meal->id . '/edit')->withInput();
        }
    }
}
