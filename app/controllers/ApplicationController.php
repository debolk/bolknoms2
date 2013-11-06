<?php

class ApplicationController extends Controller
{
    public function __construct()
    {
      $this->layout = View::make('layouts/application');

      $this->promotions();
    }

    public function promotions()
    {
      $this->layout->promoted_meals = View::make('layouts/_promotions', ['meals' => Meal::promotions()]);
    }
}
