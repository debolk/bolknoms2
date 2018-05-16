<?php

namespace App\Services;

use App\Mail\MealDestroyed;
use App\Models\Meal;
use DateTime;
use Illuminate\Support\Facades\Mail;
use Log;
use Validator;

class DestroyMealService extends Service
{
    /**
     * Set the Service
     * @param array $data data for the new Meal
     */
    public function __construct($meal)
    {
        $this->meal = $meal;
    }

    /**
     * Destroy the meal
     * @return App\Models\Meal the newly created meal
     * @throws ValidationException
     */
    public function execute()
    {
        // Remove all guests andsend them confirmation e-mails
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
