<?php

class ApplicationController extends Controller
{
    public function __construct()
    {
      $this->layout = View::make('layouts/application');

      $this->promotions();
      $this->top_eaters();
    }

    public function promotions()
    {
      $this->layout->promoted_meals = View::make('layouts/_promotions', ['meals' => Meal::promotions()]);
    }

    private function top_eaters()
    {
      $this->layout->top_eaters = View::make('layouts/_top', [
        'top_alltime' => Registration::top_alltime(),
        //'top_ytd' => Registration::top_ytd(),
      ]);
    }
}
