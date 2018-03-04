<?php

namespace App\Console\Commands\TensorFlow;

/**
 * 'Replays' known registrations.
 */
class RegistrationsIterator
{
    private $registrations;
    private $currentIndex = 0;
    private $currentTimestamp = 0;
    private $currentCounts = [];

    public function __construct($registrations)
    {
        $this->registrations = $registrations;
    }

    public function forwardTo($timestamp)
    {
        if ($timestamp < $this->currentTimestamp) {
            throw new Exception();
        }
        $this->currentTimestamp = $timestamp;


        while ($this->currentIndex < count($this->registrations) && $this->registrations[$this->currentIndex][0] <= $timestamp) {
            $r = $this->registrations[$this->currentIndex];
            $mealId = $r[1];
            if (!isset($this->currentCounts[$mealId])) {
                $this->currentCounts[$mealId] = 0;
            }

            $this->currentCounts[$mealId]++;
            $this->currentIndex++;
        }

        return 0;
    }

    public function get($id)
    {
        if (!isset($this->currentCounts[$id])) {
            return 0;
        }

        return $this->currentCounts[$id];
    }
}
