<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class Page extends Controller
{
    /**
     * Displays the disclaimer page
     * @return \Illuminate\Contracts\View\View
     */
    public function disclaimer()
    {
        return view('page/disclaimer');
    }

    /**
     * Displays the privacy statement
     * @return \Illuminate\Contracts\View\View
     */
    public function privacy()
    {
        return view('page/privacy');
    }

    /**
     * Display the rules and regulations
     * @return \Illuminate\Contracts\View\View
     */
    public function spelregels()
    {
        return view('page/spelregels');
    }

    public function apidocs(): View
    {
        return view('page/apidocs');
    }
}
