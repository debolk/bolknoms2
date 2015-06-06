<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Request;
use Log;

class Confirm extends Application
{
    public function confirm()
    {
        $registration = Registration::find((int) Request::input('id'));
        if (! $registration) {
            return response('Registratie niet gevonden', 404);
        }

        // Salt must match
        if ($registration->salt !== Request::input('salt')) {
            return response('Beveiligingscode is incorrect', 409);
        }

        // Deadline must not have passed
        if (! $registration->meal()->open_for_registrations()) {
            return response('Aanmelddeadline is al verstreken', 410);
        }

        // Confirm registration
        $registration->update('confirm', true);
        Log::debug("Registration {$registration->id} bevestigd");

        // Show confirmation page
        return $this->setPageContent('confirm/confirm', ['registration' => $registration]);
    }
}
