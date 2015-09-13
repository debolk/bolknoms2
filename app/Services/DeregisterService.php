<?php

namespace App\Services;

use App\Models\Meal;
use App\Models\Registration;
use DateTime;
use Log;

class DeregisterService extends Service
{
    private $registration;
    private $ignore_closing;

    /**
     * Set the Service
     * @param Registration $registration
     * @param boolean $ignore_closing ignores the closing time limitation
     */
    public function __construct(Registration $registration, $ignore_closing = false)
    {
        $this->registration = $registration;
        $this->ignore_closing = $ignore_closing;
    }

    /**
     * Create a new Meal
     * @return boolean
     * @throws ValidationException
     */
    public function execute()
    {
        // Find the meal
        $meal = $this->registration->meal;

        // Check if the meal is still open
        if (!$this->ignore_closing && !$meal->open_for_registrations()) {
            throw new MealDeadlinePassedException();
        }

        // Store data for logging purposes
        $id = $this->registration->id;
        $name = $this->registration->name;

        // Log action
        \Log::info("Afgemeld $name (ID: $id) voor $meal (ID: $meal->id)");

        // Remove the registration
        return $this->registration->delete();
    }
}
