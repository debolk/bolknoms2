<?php

namespace App\Services;

use App\Mail\MealDestroyed;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DestroyMealService extends Service
{
    private $meal;

    /**
     * Set the Service
     * @param \App\Models\Meal $meal
     */
    public function __construct($meal)
    {
        $this->meal = $meal;
    }

    /**
     * Destroy the meal
     * @return bool
     * @throws \App\Services\ValidationException
     */
    public function execute()
    {
        // Remove all guests and send them notification e-mails
        foreach ($this->meal->registrations as $registration) {
            if ($registration->email == "invalid@nieuwedelft.nl.") {
                continue;
            }
            Mail::send(new MealDestroyed($this->meal, $registration));
            $registration->delete();
        }

        // Write loggin
        Log::info('Maaltijd verwijderd: '.$this->meal);

        // Remove the meal
        return $this->meal->delete();
    }
}
