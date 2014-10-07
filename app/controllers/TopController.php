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
            'statistics_ytd' => Registration::top_ytd()->get(),
            'statistics_alltime' => Registration::top_alltime()->get(),
        ];

        $this->layout->content = View::make('top/index', $data);
    }
}
