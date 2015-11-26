<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Application;

class Dashboard extends Application
{
    /**
     * List all past and current meals
     * @return View
     */
    public function index()
    {
        return view('administration/dashboard/index');
    }
}