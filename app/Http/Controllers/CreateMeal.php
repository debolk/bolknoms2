<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Flash;
use App\Models\Meal;
use Illuminate\Http\Request;
use DateTime;
use Validator;
use Session;
use Input;
use Log;

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
        $meal_data = $request->all();

        // Use todays date as defaults if none are given
        if (empty($meal_data['meal_timestamp'])) {
            $meal_data['meal_timestamp'] = date('d-m-Y').' 18:30';
        }
        if (empty($meal_data['locked_timestamp'])) {
            $meal_data['locked_timestamp'] = date('d-m-Y').' 15:00';
        }

        // Validate the resulting input
        $validator = Validator::make($meal_data, [
            'meal_timestamp'   => ['date_format:d-m-Y G:i', 'required', 'after:now', 'unique:meals'],
            'locked_timestamp' => ['date_format:d-m-Y G:i', 'required', 'after:now']
        ],[
             'meal_timestamp.date_format'   => 'De ingevulde maaltijd is ongeldig (formaat DD-MM-YYYY HH:MM)',
             'meal_timestamp.required'      => 'De ingevulde maaltijd is ongeldig (formaat DD-MM-YYYY HH:MM)',
             'meal_timestamp.after'         => 'Je kunt geen maaltijden aanmaken in het verleden',
             'meal_timestamp.unique'        => 'Er is al een maaltijd op deze datum en tijd',
             'locked_timestamp.date_format' => 'De ingevulde sluitingstijd is ongeldig (formaat DD-MM-YYYY HH:MM)',
             'locked_timestamp.required'    => 'De ingevulde sluitingstijd is ongeldig (formaat DD-MM-YYYY HH:MM)',
             'locked_timestamp.after'       => 'De deadline voor aanmelding mag niet al geweest zijn'
        ]
        );

        if ($validator->passes()) {

            // Format dates to database compatible values
            $meal_data['meal_timestamp']   = DateTime::createFromFormat('d-m-Y G:i', $meal_data['meal_timestamp']);
            $meal_data['locked_timestamp'] = DateTime::createFromFormat('d-m-Y G:i', $meal_data['locked_timestamp']);

            // Save new meal
            $meal = new Meal($meal_data);
            if ($meal->save()) {
                Log::info("Nieuwe maaltijd: $meal->id|$meal->meal_timestamp|$meal->event");
                Flash::set(Flash::SUCCESS, 'Maaltijd toegevoegd op '.$meal);
                return redirect('/administratie');
            }
            else {
                Flash::set(Flash::ERROR, 'Maaltijd kon niet worden toegevoegd');
            }
        }
        else {
            Session::flash('validation_errors', $validator->messages());
            // Repopulate the form
            Input::flash();
        }
        return redirect('/administratie/nieuwe_maaltijd');
    }
}
