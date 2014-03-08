<?php

class ApplicationController extends Controller
{
    /**
     * Setups the controller, loading data on statistics
     * @return void
     */
    public function __construct()
    {
      $this->layout = View::make('application/application');

      $this->promotions();
      $this->top_eaters();
    }

    /**
     * Shows upcoming promoted meals
     * @return View
     */
    public function promotions()
    {
      $this->layout->promoted_meals = View::make('application/_promotions', ['meals' => Meal::promotions()->available()->get()]);
    }

    /**
     * Show the list of people who've eaten the most in all time
     * @return View
     */
    private function top_eaters()
    {
      $this->layout->top_eaters = View::make('application/_top', [
        'top_alltime' => Registration::top_alltime(),
        'top_ytd' => Registration::top_ytd(),
      ]);
    }
}
