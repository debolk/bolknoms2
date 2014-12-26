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
    }
}
