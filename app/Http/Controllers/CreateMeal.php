<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Flash;
use App\Models\Meal;

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
    public function create()
    {
        // Build candidate object, using today's data as defaults
        $meal_data = [
            'date'        => \Request::input('date', date('d-m-Y')),
            'locked_date' => \Request::input('locked_date', date('d-m-Y')),
            'locked'      => \Request::input('locked', '15:00'),
            'mealtime'    => \Request::input('mealtime', '18:30'),
            'event'       => \Request::input('event', null),
        ];
        if (empty($meal_data['date'])) {
            $meal_data['date'] = date('d-m-Y');
        }
        if (empty($meal_data['locked_date'])) {
            $meal_data['locked_date'] = date('d-m-Y');
        }
        if (empty($meal_data['locked'])) {
            $meal_data['locked'] = '15:00';
        }
        if (empty($meal_data['mealtime'])) {
            $meal_data['mealtime'] = '18:30';
        }

        // Format Dutch date to DB date (dd-mm-yyyy -> yyyy-mm-dd)
        $date = \DateTime::createFromFormat('d-m-Y', $meal_data['date']);
        $meal_data['date'] = ($date) ? ($date->format('Y-m-d')) : (null);
        $locked_date = \DateTime::createFromFormat('d-m-Y', $meal_data['locked_date']);
        $meal_data['locked_date'] = ($locked_date) ? ($locked_date->format('Y-m-d')) : (null);

        // Validate the resulting input
        $validator = \Validator::make($meal_data, [
            'date' => ['date', 'required', 'unique:meals', 'after:yesterday'],
            'locked_date' => ['date', 'required', 'after:yesterday'],
            'locked' => ['regex:/^[0-2][0-9]:[0-5][0-9]$/'],
            'mealtime' => ['regex:/^[0-2][0-9]:[0-5][0-9]$/'],
        ],[
            'date.required' => 'De ingevulde datum is ongeldig',
            'date.date' => 'De ingevulde datum is ongeldig',
            'date.unique' => 'Op de ingevulde datum is al een maaltijd gepland',
            'date.after' => 'Je kunt geen maaltijden aanmaken in het verleden',
            'locked_date.required' => 'De ingevulde sluitingsdatum is ongeldig',
            'locked_date.date' => 'De ingevulde sluitingsdatum is ongeldig',
            'locked_date.after' => 'Je kunt geen maaltijden aanmaken met een sluitingsdatum die al verstreken is',
            'locked.regex' => 'De sluitingstijd moet als HH:MM ingevuld zijn',
            'mealtime.regex' => 'De etenstijd moet als HH:MM ingevuld zijn',
        ]
        );

        if ($validator->passes()) {
            // Save new meal
            $meal = new Meal;
            $meal->date = $meal_data['date'];
            $meal->locked_date = $meal_data['locked_date'];
            $meal->locked = $meal_data['locked'];
            $meal->mealtime = $meal_data['mealtime'];
            $meal->event = $meal_data['event'];

            if ($meal->save()) {
                \Log::info("Nieuwe maaltijd: $meal->id|$meal->date|$meal->event");
                Flash::set(Flash::SUCCESS, 'Maaltijd toegevoegd op '.$meal);
                return redirect('/administratie');
            }
            else {
                Flash::set(Flash::ERROR, 'Maaltijd kon niet worden toegevoegd');
            }
        }
        else {
            \Session::flash('validation_errors', $validator->messages());
            // Repopulate the form
            \Input::flash();
        }
        return redirect('/administratie/nieuwe_maaltijd');
    }
}
