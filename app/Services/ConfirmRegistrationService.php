<?php

namespace App\Services;

use Log;
use Validator;
use DateTime;
use App\Models\Registration;
use Exception;

class ConfirmRegistrationService extends Service
{
    private $registration;
    private $salt;

    public function __construct(Registration $registration, $salt)
    {
        $this->registration = $registration;
        $this->salt = $salt;
    }

    /**
     * Confirm a registration
     * @return \App\Models\Meal|null the newly created meal
     * @throws SaltMismatchException
     * @throws MealDeadlinePassedException
     * @return ?Registration
     */
    public function execute()
    {
        // Salt must match
        if ($this->registration->salt !== $this->salt) {
            throw new SaltMismatchException();
        }

        // Deadline must not have passed
        if (! $this->registration->meal->open_for_registrations()) {
            throw new MealDeadlinePassedException();
        }

        // Confirm registration
        $this->registration->confirmed = true;
        if (!$this->registration->save()) {
            return null;
        }

        Log::info("Registration {$this->registration->id} bevestigd");
        return $this->registration;
    }
}

class SaltMismatchException extends Exception
{
}
