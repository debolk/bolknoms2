<?php

namespace App\Services;

use App\Mail\MealDestroyed;
use App\Models\Meal;
use DateTime;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

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
        foreach ($this->meal->registrations()->get() as $registration) {
            Mail::send(new MealDestroyed($this->meal, $registration));
            $registration->delete();
        }

        // Write loggin
        Log::info('Maaltijd verwijderd: '.$this->meal);

        // Remove the meal
        return $this->meal->delete();
    }
}
