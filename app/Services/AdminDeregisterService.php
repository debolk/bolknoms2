<?php

namespace App\Services;

use App\Models\Meal;
use App\Models\Registration;
use DateTime;
use Illuminate\Support\Facades\Log;

/**
 * Removes a registration from a meal
 * for administrator users
 */
class AdminDeregisterService extends Service
{
    private $registration;

    /**
     * Set the Service
     * @param Registration $registration
     */
    public function __construct(Registration $registration)
    {
        $this->registration = $registration;
    }

    /**
     * Remove a registration from a meal
     * @return bool
     */
    public function execute()
    {
        // Find the meal
        $meal = $this->registration->meal;

        // Store data for logging purposes
        $id = $this->registration->id;
        $name = $this->registration->name;

        // Log action
        Log::info("Afgemeld $name (ID: $id) voor $meal (ID: $meal->id)");

        // Remove the registration
        return $this->registration->delete();
    }
}
