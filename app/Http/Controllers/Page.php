<?php

namespace App\Http\Controllers;

class Page extends Application
{
    /**
     * Displays the disclaimer page
     * @return View
     */
    public function disclaimer()
    {
        return view('page/disclaimer');
    }

    /**
     * Displays the privacy statement
     * @return View
     */
    public function privacy()
    {
        return view('page/privacy');
    }

    /**
     * Display the rules and regulations
     * @return View
     */
    public function spelregels()
    {
        return view('page/spelregels');
    }
}
