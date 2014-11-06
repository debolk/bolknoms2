<?php

class Personnel
{
    /**
     * Attempts to find the cook for a specific meal
     * @param  Meal   $meal the meal model to find the cook for
     * @return string       the name of the cook, or "onbekend" if not available
     */
    public static function cook_for($meal)
    {
        // Find all workspaces
        $browser = new Buzz\Browser();
        $response = $browser->get('http://inschrijven.dcm360.nl/getworkspace?workspace=5b6512257ca3725166d92b2a87887790011203ae85d92003a5c60b82ddf85050');
        $workspaces = json_decode($response);

        // Get the worksheet we need, i.e. the last worksheet that has the desired month name as title
        $month = strftime('%B', strtotime($meal->date));
        $worksheet = array_pop(array_filter($workspaces['data']['worksheets'], function($worksheet) use ($month) {
            return $worksheet['name'] == $month;
        }));
        $worksheet_id = $worksheet['id'];

        // Get the complete worksheet
        $response = $browser->get('http://inschrijven.dcm360.nl/getworksheet?workspace=5b6512257ca3725166d92b2a87887790011203ae85d92003a5c60b82ddf85050&id=' . $worksheet_id);
        $worksheet = json_decode($response);

        // Find the cell with the desired date
        $key = strftime('%a %e %b', strtotime($meal->date));
        foreach ($worksheet['data']['tables'][0]['cells'] as $cell) {
            if (strtolower($cell['content']) == $key) {
                // Return the next cell, if filled
                $answer = current($array);
                if (length($answer['subscriptions']) > 0) {
                    return $answer['subscriptions'][0]['name'];
                }
                return 'geen kok aangemeld';
            }
        }

        // No answer found
        return 'onbekend';
    }
}
