<?php

namespace App\Services;

use App\Models\Registration;
use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * Removes a registration from a meal
 * for normal usage
 */
class DeregisterService extends Service
{
    public function __construct(private Registration $registration, private User $user)
    {
    }

    /**
     * Remove a registration from a meal
     * @return bool
     * @throws MealDeadlinePassedException
     */
    public function execute()
    {
        // Find the meal
        $meal = $this->registration->meal;

        // Check if the meal is still open
        if (!$meal->open_for_registrations()) {
            throw new MealDeadlinePassedException();
        }

        // Store data for logging purposes
        $id = $this->registration->id;
        $name = $this->registration->name;

        // Log action
        Log::info("Afgemeld $name (ID: $id) voor $meal (ID: $meal->id)");

        // Remove any collectibles
        $collectible = $this->registration->meal->awardsCollectible;
        if ($collectible) {
            $collectible->stripFrom($this->user);
        }

        // Remove the registration
        return $this->registration->delete();
    }
}
