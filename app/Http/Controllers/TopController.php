<?php

namespace App\Http\Controllers;

use App\Models\Registration;

class TopController extends ApplicationController
{
    /**
     * Show a list of all eaters
     */
    public function index()
    {
        return $this->setPageContent(view('top/index', [
            'statistics_ytd' => Registration::top_ytd(),
            'statistics_alltime' => Registration::top_alltime(),
        ]));
    }
}
