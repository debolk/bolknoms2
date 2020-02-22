<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;

class Dashboard extends Controller
{
    /**
     * List all past and current meals
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('administration/dashboard/index');
    }
}
