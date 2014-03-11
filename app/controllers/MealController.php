<?php

class MealController extends ApplicationController
{
    /**
     * Shows the page for creating a new meal
     * @return View
     */
    public function new_meal()
    {
        $this->layout->content = View::make('meal/nieuwe_maaltijd', [
            'meal' => new Meal,
        ]);
    }

    /**
     * Processes the new meal form to create a new meal
     * @return Redirect
     */
    public function create()
    {
        $meal = new Meal;

        $validator = Validator::make(Input::all(), [
            'date' => ['required', 'date', 'unique:meals', 'after:yesterday'],
            'locked' => ['required', 'regex:/^[0-2][0-9]:[0-5][0-9]$/'],
            'promoted' => ['in:0,1'],
        ],[
            'date.required' => 'Je moet een datum invullen',
            'date.date' => 'De ingevulde datum is ongeldig',
            'date.unique' => 'Op de ingevulde datum is al een maaltijd gepland',
            'date.after' => 'Je kunt geen maaltijden aanmaken in het verleden',
            'locked.required' => 'Je moet een sluitingstijd van de inschrijving invullen',
            'locked.regex' => 'De sluitingstijd moet als HH:MM ingevuld zijn',
            'promoted.in' => 'Je kunt alleen ja of nee kiezen voor promotie',
        ]
        );

        if ($validator->passes()) {
            // Set data
            $meal->date = e(Input::get('date'));
            $meal->locked = e(Input::get('locked', '15:00'));
            $meal->event = e(Input::get('event', null));
            $meal->promoted = Input::get('promoted', false);

            if ($meal->save()) {
                Log::info("Nieuwe maaltijd: $meal->id|$meal->date");
                Flash::set(Flash::SUCCESS, 'Maaltijd toegevoegd op '.$meal);
                return Redirect::to('/administratie');
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
        return Redirect::to('/administratie/nieuwe_maaltijd');
    }

    /**
     * Shows the form to edit an existing meal
     * @param int $id the id of the meal to edit
     * @return View
     */
    public function edit($id)
    {
        $meal = Meal::find($id);

        if (!$meal) {
            App::abort(404, "Maaltijd niet gevonden");
        }

        $this->layout->content = View::make('meal/bewerk', ['meal' => $meal]);
    }

    /**
     * Processes the form to edit an existing meal
     * @param int $id the id of the meal to update
     * @return Redirect
     */
    public function update($id)
    {
        $meal = Meal::find($id);
        if (!$meal) {
            App::abort(404, 'Maaltijd niet gevonden');
        }

        $validator = Validator::make(Input::all(), [
            'date' => ['required', 'date', 'unique:meals,date,'.$id, 'after:yesterday'],
            'locked' => ['required', 'regex:/^[0-2][0-9]:[0-5][0-9]$/'],
            'promoted' => ['in:0,1'],
        ],[
            'date.required' => 'Je moet een datum invullen',
            'date.date' => 'De ingevulde datum is ongeldig',
            'date.unique' => 'Op de ingevulde datum is al een andere maaltijd gepland',
            'date.after' => 'Je kunt geen maaltijden aanmaken in het verleden',
            'locked.required' => 'Je moet een sluitingstijd van de inschrijving invullen',
            'locked.regex' => 'De sluitingstijd moet als HH:MM ingevuld zijn',
            'promoted.in' => 'Je kunt alleen ja of nee kiezen voor promotie',
        ]
        );

        if ($validator->passes()) {
            $data = [
                'date' => e(Input::get('date')),
                'locked' => e(Input::get('locked')),
                'event' => e(Input::get('event')),
                'promoted' => Input::get('promoted', false)
            ];
            if ($meal->update($data)) {
                Flash::set(Flash::SUCCESS,"Maaltijd ge&uuml;pdate");
                Log::info("Maaltijd veranderd: $meal->id|$meal->date");
                return Redirect::to('/administratie');
            }
            else {
                Flash::set(Flash::ERROR, 'Maaltijd kon niet worden bewerkt');
            }
        }
        else {
            Session::flash('validation_errors', $validator->messages());
            // Repopulate the form
            Input::flash();
        }
        return Redirect::to('/administratie/bewerk/'.$meal->id);
    }

    /**
     * Prints an array (json-encoded) of all upcoming dates with meals planned
     * used for the date-picker to hide all dates already filled
     * @return string
     */
    public function gevulde_dagen()
    {
        $id = Input::get('meal_id', null);

        $meals = Meal::upcoming()->get();
        $dates = array();
        foreach ($meals as $meal) {
            if ($id !== $meal->id) {
                $dates[] = $meal->date;
            }
        }
        header('Content-Type: application/json');
        return json_encode($dates);
    }
}
