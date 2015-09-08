<?php

namespace App\Services;

use Log;
use Validator;
use DateTime;
use App\Models\Meal;
use App\Http\Helpers\Mailer;

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
        // Send an e-mail to the registrations for confirmation
        Mailer::mealIsDestroyedEmail($this->meal);

        // Remove all guests
        foreach ($this->meal->registrations()->get() as $registration) {
            $registration->delete();
        }

        // Write loggin
        Log::info('Maaltijd verwijderd: '.$this->meal);

        // Remove the meal
        return $this->meal->delete();
    }
}
