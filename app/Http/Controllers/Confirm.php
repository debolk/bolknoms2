<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Request;
use Log;

class Confirm extends Application
{
    public function confirm($id, $salt)
    {
        $registration = Registration::find((int) $id);
        if (! $registration) {
            return response('Registratie niet gevonden', 404);
        }

        // Salt must match
        if ($registration->salt !== $salt) {
            return response('Beveiligingscode is incorrect', 409);
        }

        // Deadline must not have passed
        if (! $registration->meal->open_for_registrations()) {
            return response('Aanmelddeadline is al verstreken', 410);
        }

        // Confirm registration
        $registration->confirmed = true;
        $registration->save();

        Log::debug("Registration {$registration->id} bevestigd");

        // Show confirmation page
        return $this->setPageContent(view('confirm/confirm', ['registration' => $registration]));
    }
}
