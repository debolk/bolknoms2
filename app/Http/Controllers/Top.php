<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Contracts\View\View;

class Top extends Controller
{
    /**
     * Show a list of all eaters
     */
    public function index(): View
    {
        return view('top/index', [
            'statistics_ytd' => Registration::top_ytd(),
            'statistics_alltime' => Registration::top_alltime(),
        ]);
    }
}
