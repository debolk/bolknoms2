<?php

class TopController extends ApplicationController
{
    /**
     * Show a list of all eaters
     * @return [type] [description]
     */
    public function index()
    {
        $data = [
            'registrations' => Registration::top_ytd()
        ];

        $this->layout->content = View::make('top/index', $data);
    }
}
