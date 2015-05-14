<?php

namespace App\Http\Controllers;

class PageController extends ApplicationController
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
}
