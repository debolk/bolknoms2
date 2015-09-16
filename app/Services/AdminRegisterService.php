<?php

namespace App\Services;

use App\Http\Helpers\Mailer;
use App\Models\Meal;
use App\Models\Registration;
use App\Models\User;
use DateTime;
use Validator;
use Log;
use Exception;

/**
 * AdminRegisterService adds a new Registration to a Meal
 * for administrators. If a user without administrative powers
 * adds a registration, RegisterService should be used instead.
 *
 * Note that it is not the admin itself that is registered to the
 * meal, but another user.
 */
class AdminRegisterService extends Service
{
    private $data;

    /**
     * Set the Service
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Register for a meal
     * @return boolean
     * @throws ValidationException
     * @throws UserBlockedException
     * @throws ModelNotFoundException
     * @throws DoubleRegistrationException
     */
    public function execute()
    {
        // Meal must exist
        $meal = Meal::findOrFail($this->data['meal_id']);

        // Submitted data must be complete and valid
        $validator = Validator::make($this->data, [
            'name'    => ['required'],
            'user_id' => ['exists:users,id']
        ],[
            'name.required'  => 'Je moet je naam invullen',
            'email.required' => 'Je moet je e-mailadres invullen',
            'email.email'    => 'Het ingevulde e-mailadres is ongeldig',
            'email.email'    => 'Het ingevulde e-mailadres is ongeldig',
            'user_id.exist'  => 'De gevraagde gebruiker is niet bekend'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->messages());
        }

        // Get the user if appropriate
        $user = null;
        if (isset($this->data['user_id'])) {
            $user = User::findOrFail($this->data['user_id']);
        }

        // User may not be blocked
        if ($user && $user->blocked) {
            throw new UserBlockedException;
        }

        // Users may not register twice
        if ($user && $user->registeredFor($meal)) {
            throw new DoubleRegistrationException;
        }

        // Create the registration
        $registration = new Registration($this->data);
        $registration->meal_id = $this->data['meal_id'];
        $registration->confirmed = true;
        $registration->save();

        // Add the user if appropriate
        if ($user) {
            $registration->user_id = $user->id;
            $registration->username = $user->username;
            $registration->save();
        }

        // Log action
        Log::info("Aangemeld: $registration->id|$registration->name");

        // Return data of the new registration
        return $registration;
    }
}

class UserBlockedException extends Exception {};
class DoubleRegistrationException extends Exception {};