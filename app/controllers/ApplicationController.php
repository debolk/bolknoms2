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

        // Find the desired Javascript file
        if (preg_match('/^([a-zA-Z]+)Controller/', Route::currentRouteAction(), $controller)) {
            $file = strtolower($controller[1]);
            if (file_exists(dirname(__FILE__) . "/../../public/javascripts/$file.js")) {
                $this->layout->javascript = $file;
            }
        }
    }
}
