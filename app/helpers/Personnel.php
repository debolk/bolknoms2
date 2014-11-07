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
        // Get all worksheets
        $browser = new Buzz\Browser();
        $response = $browser->get('http://inschrijven.dcm360.nl/getworkspace?workspace=5b6512257ca3725166d92b2a87887790011203ae85d92003a5c60b82ddf85050');
        if (! $response->isOk()) {
            return 'onbekend';
        }
        $worksheets = json_decode($response->getContent())->data->worksheets;

        // Find the worksheet we need, i.e. the last worksheet that has the desired month name as title
        $month = strftime('%B', strtotime($meal->date));
        $worksheets = array_filter($worksheets, function($worksheet) use ($month) {
            return strtolower($worksheet->name) == strtolower($month);
        });
        if ($worksheets == null || count($worksheets) == 0) {
            return 'onbekend';
        }
        $worksheet = array_pop($worksheets);

        // Get the cells in the worksheet
        $response = $browser->get('http://inschrijven.dcm360.nl/getworksheet?workspace=5b6512257ca3725166d92b2a87887790011203ae85d92003a5c60b82ddf85050&id=' . $worksheet->id);
        $cells = json_decode($response->getContent())->data->tables[0]->cells;
        if (! $response->isOk()) {
            return 'onbekend';
        }

        // Find the cell with the desired date
        $key = strftime('%a %e %b', strtotime($meal->date));
        foreach ($cells as $cell) {
            if (strtolower($cell->content) == $key) {
                // Return the next cell, if filled
                $answer = current($cells);
                if (count($answer->subscriptions) > 0) {
                    return $answer->subscriptions[0]->name;
                }
                return 'geen kok aangemeld';
            }
        }

        // No answer found
        return 'C onbekend';
    }
}
