<?php

namespace App\Services;

use App\Mail\RegistrationConfirmation;
use App\Models\Meal;
use App\Models\Registration;
use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Mail;
use Log;
use Validator;

/**
 * RegisterService adds a new Registration to a Meal
 * for normal usage. If a user with administrative powers
 * adds a registration, AdminRegisterService should be used instead.
 */
class RegisterService extends Service
{
    private array $data;
    private ?User $current_user;

    public function __construct(array $data, ?User $current_user)
    {
        $this->data = $data;
        $this->current_user = $current_user;
    }

    /**
     * Register for a meal
     */
    public function execute(): Registration
    {
        // Meal must exist
        $meal = Meal::findOrFail($this->data['meal_id']);

        // Meal must be open for registrations, unless we allow ignoring this requirement
        if (!$meal->open_for_registrations()) {
            throw new MealDeadlinePassedException();
        }

        // Submitted data must be complete and valid
        $validator = Validator::make($this->data, [
            'email'   => ['required', 'email'],
            'name'    => ['required'],
            'user_id' => ['exists:users,id']
        ], [
            'name.required'  => 'Je moet je naam invullen',
            'email.required' => 'Je moet je e-mailadres invullen',
            'email.email'    => 'Het ingevulde e-mailadres is ongeldig',
            'user_id.exist'  => 'De gevraagde gebruiker is niet bekend'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }

        // Get the user if appropriate
        $user = null;
        if (isset($this->data['user_id'])) {
            $user = User::findOrFail($this->data['user_id']);
        }

        // User may not be blocked
        if ($user && $user->blocked) {
            throw new UserBlockedException();
        }

        // Users may not register twice
        if ($user && $user->registeredFor($meal)) {
            throw new DoubleRegistrationException();
        }

        // Create the registration
        $registration = new Registration($this->data);
        $registration->meal_id = $this->data['meal_id'];
        $registration->confirmed = false;
        $registration->save();

        // Add the creating user logging if known
        if ($this->current_user) {
            $registration->created_by = $this->current_user->id;
            $registration->save();
        }

        // Auto-confirm registration if appropriate
        if ($user) {
            $registration->user_id = $user->id;
            $registration->username = $user->username;
            $registration->confirmed = true;
            $registration->save();
        } else {
            // Send e-mail to ask for confirmation
            Mail::send(new RegistrationConfirmation($registration));
        }

        // Log action
        Log::info("Aangemeld: $registration->id|$registration->name");

        // Return data of the new registration
        return $registration;
    }
}
