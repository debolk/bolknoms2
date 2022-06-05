<?php

namespace App\Services;

use App\Models\Registration;
use DateTime;
use Exception;
use Log;
use Validator;

class ConfirmRegistrationService extends Service
{
    private Registration $registration;

    private string $salt;

    public function __construct(Registration $registration, string $salt)
    {
        $this->registration = $registration;
        $this->salt = $salt;
    }

    /**
     * Confirm a registration
     */
    public function execute(): ?Registration
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
        if (! $this->registration->save()) {
            return null;
        }

        Log::info("Registration {$this->registration->id} bevestigd");

        return $this->registration;
    }
}

class SaltMismatchException extends Exception
{
}
