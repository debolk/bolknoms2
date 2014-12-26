<?php

class PrintController extends ApplicationController
{
    /**
     * Prints a checklist for crossing off visiting users
     * not intended to be viewed, only printed
     * @param int $id the id of the meal for which registrations are requested
     * @return View
     */
    public function checklist($id)
    {
        $meal = Meal::find($id);
        if (!$meal) {
            App::abort(404, 'Maaltijd niet gevonden');
        }

        return View::make('print/checklist', ['meal' => $meal]);
    }
}