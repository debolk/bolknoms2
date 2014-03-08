<?php

class PageController extends ApplicationController
{
    /**
     * Displays the disclaimer page
     * @return View
     */
    public function disclaimer()
    {
        $this->layout->content = View::make('page/disclaimer');
    }

    /**
     * Displays the privacy statement
     * @return View
     */
    public function privacy()
    {
        $this->layout->content = View::make('page/privacy');
    }
}
