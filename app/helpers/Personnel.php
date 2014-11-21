<?php

class Personnel
{
    /**
     * The meal which concerns this personnel file
     * @var Meal
     */
    private $meal;

    /**
     * Construct a personnel file for a meal
     * @param Meal $meal an existing meal object
     */
    public function __construct($meal)
    {
        $this->meal = $meal;

        $this->memcache = new Memcached();
        $this->memcache->setOption(Memcached::OPT_BINARY_PROTOCOL, true);
        $this->memcache->addServers(array_map(function($server) {return explode(':', $server, 2); }, explode(',', getenv('MEMCACHEDCLOUD_SERVERS'))));
        $this->memcache->setSaslAuthData(getenv('MEMCACHEDCLOUD_USERNAME'), getenv('MEMCACHEDCLOUD_PASSWORD'));
    }

    /**
     * Find the cook for the meal
     * @return string the name of the cook
     */
    public function cook()
    {
        $cook = $this->memcache->get('cook_'.$this->meal->id);
        if (!$cook) {
            $cook = $this->query_kcb();
            $this->memcache->set('cook_'.$this->meal->id, $cook, 60*60);
        }
        return $cook;
    }

    /**
     * Search the KCB service for the name of the cook
     * @return string the name of the cook if found, or an acceptable error message if none is found
     */
    public function query_kcb()
    {
        $headers = ['Accept' => 'application/json'];
        $options = ['timeout' => 3];

        // Get all worksheets
        try {
            $response = Requests::get('http://inschrijven.dcm360.nl/getworkspace?workspace=5b6512257ca3725166d92b2a87887790011203ae85d92003a5c60b82ddf85050', $headers, $options);
            
        } catch (Requests_Exception $e) {
            Log::warning("Failed to retrieve cook for {$this->meal->date}: {$e->getMessage()}");
            return 'onbekend';
        }
        if (! $response->status_code === 200) {
            return 'onbekend';
        }

        // Find the worksheet we need, i.e. the last worksheet that has the desired month name as title
        $worksheets = json_decode($response->body)->data->worksheets;
        $month = strftime('%B', strtotime($this->meal->date));
        $worksheets = array_filter($worksheets, function($worksheet) use ($month) {
            return strtolower($worksheet->name) == strtolower($month);
        });
        if ($worksheets == null || count($worksheets) == 0) {
            return 'onbekend';
        }
        $worksheet = array_pop($worksheets);

        // Get the current worksheet
        try {
            $response = Requests::get('http://inschrijven.dcm360.nl/getworksheet?workspace=5b6512257ca3725166d92b2a87887790011203ae85d92003a5c60b82ddf85050&id=' . $worksheet->id, $headers);
        } catch (Requests_Exception $e) {
            Log::warning("Failed to retrieve cook for {$this->meal->date}: {$e->getMessage()}");
            return 'onbekend';
        }
        if (! $response->status_code === 200) {
            return 'onbekend';
        }

        // Find the cell with the desired date
        $cells = json_decode($response->body)->data->tables[0]->cells;
        $key = strftime('%a %e %b', strtotime($this->meal->date));
        foreach ($cells as $cell) {
            if (strtolower($cell->content) == $key) {
                // Return the next cell, if filled
                $answer = current($cells);
                if (count($answer->subscriptions) > 0) {
                    return $answer->subscriptions[0]->name;
                }
                else {
                    return 'geen kok aangemeld';
                }
            }
        }

        // No answer found
        return 'onbekend';
    }
}
