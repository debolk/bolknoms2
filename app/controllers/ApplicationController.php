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
    }

    /**
     * Shows upcoming promoted meals
     * @return View
     */
    public function promotions()
    {
      $this->layout->promoted_meals = View::make('application/_promotions', ['meals' => Meal::promotions()->available()->get()]);
    }
}
