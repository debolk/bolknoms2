<?php

namespace App\Http\Controllers;

use \App\Http\Helpers\Flash;
use \App\Models\Meal;
use App;
use Illuminate\Http\Request;
use DateTime;
use Session;
use Log;
use Validator;

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

        return $this->setPageContent(view('meal/edit', ['meal' => $meal]));
    }

    /**
     * Processes the edit meal form to update a meal
     * @return Redirect
     */
    public function update($id, Request $request)
    {
        // Only update existing meals
        $meal = Meal::find($id);
        if (!$meal) {
            App::abort(404, "Maaltijd niet gevonden");
        }

        // Construct candidate object
        $meal_data = $request->all();

        // Validate the resulting input
        $validator = Validator::make($meal_data, [
            'meal_timestamp'   => ['date_format:d-m-Y G:i', 'required', 'unique:meals,meal_timestamp,'.$meal->id],
            'locked_timestamp' => ['date_format:d-m-Y G:i', 'required']
        ],[
            'meal_timestamp.date_format'   => 'De ingevulde maaltijd is ongeldig (formaat DD-MM-YYYY HH:MM)',
            'meal_timestamp.required'      => 'De ingevulde maaltijd is ongeldig (formaat DD-MM-YYYY HH:MM)',
            'meal_timestamp.unique'        => 'Er is al een maaltijd op deze datum en tijd',
            'locked_timestamp.date_format' => 'De ingevulde sluitingstijd is ongeldig (formaat DD-MM-YYYY HH:MM)',
            'locked_timestamp.required'    => 'De ingevulde sluitingstijd is ongeldig (formaat DD-MM-YYYY HH:MM)',
        ]
        );

        if ($validator->passes()) {

            // Format dates to database compatible values
            $meal_data['meal_timestamp']   = DateTime::createFromFormat('d-m-Y G:i', $meal_data['meal_timestamp']);
            $meal_data['locked_timestamp'] = DateTime::createFromFormat('d-m-Y G:i', $meal_data['locked_timestamp']);

            // Update meal in database
            $meal->update($meal_data);
            if ($meal->save()) {
                Log::info("Maaltijd geupdate: $meal->id|$meal->date|$meal->event");
                Flash::set(Flash::SUCCESS, 'Maaltijd geupdate');
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
