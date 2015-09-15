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

class RegisterService extends Service
{
    private $data;
    private $is_admin;

    /**
     * Set the Service
     * @param $data
     * @param boolean $is_admin ignores the closing time limitation
     */
    public function __construct($data, $is_admin = false)
    {
        $this->data = $data;
        $this->is_admin = $is_admin;
    }

    /**
     * Register for a meal
     * @return boolean
     * @throws ValidationException
     */
    public function execute()
    {
        // Meal must exist
        $meal = Meal::findOrFail($this->data['meal_id']);

        // Meal must be open for registrations, unless we allow ignoring this requirement
        if (!$this->is_admin && !$meal->open_for_registrations()) {
            throw new MealDeadlinePassedException;
        }

        // Submitted data must be complete and valid
        $rules = [
            'name'    => ['required'],
            'user_id' => ['exists:users,id']
        ];
        if (! $this->is_admin) {
            $rules['email'] = ['required', 'email'];
        }

        $validator = Validator::make($this->data, $rules,[
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
        $registration->confirmed = false;
        $registration->save();

        // Auto-confirm registration if appropriate
        if ($user) {
            $registration->user_id = $user->id;
            $registration->username = $user->username;
            $registration->confirmed = true;
            $registration->save();
        }
        else {
            // Send e-mail to ask for confirmation
            if ($registration->email) {
                Mailer::confirmationEmail($registration);
            }
        }

        // Log action
        Log::info("Aangemeld: $registration->id|$registration->name");

        // Return data of the new registration
        return $registration;
    }
}

class UserBlockedException extends Exception {};
class DoubleRegistrationException extends Exception {};
