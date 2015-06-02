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
        return $this->setPageContent(view('page/disclaimer'));
    }

    /**
     * Displays the privacy statement
     * @return View
     */
    public function privacy()
    {
        return $this->setPageContent(view('page/privacy'));
    }

    /**
     * Display the advantages of signing in
     * @return View
     */
    public function voordeelaccount()
    {
        return $this->setPageContent(view('page/voordeelaccount'));
    }

    /**
     * Display the rules and regulations
     * @return View
     */
    public function spelregels()
    {
        return $this->setPageContent(view('page/spelregels'));
    }
}
