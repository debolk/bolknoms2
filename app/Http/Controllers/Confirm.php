<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Request;
use Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\ConfirmRegistrationService;
use App\Services\SaltMismatchException;
use App\Services\MealDeadlinePassedException;

class Confirm extends Application
{
    public function confirm($id, $salt)
    {
        try {
            $registration = Registration::findOrFail($id);
        }
        catch (ModelNotFoundException $e) {
            return $this->userFriendlyError(404, 'Aanmelding bestaat niet');
        }

        // Confirm registration
        try {
            $confirm = with(new ConfirmRegistrationService($registration, $salt))->execute();
        }
        catch (SaltMismatchException $e) {
            return $this->userFriendlyError(400, 'Beveiligingscode klopt niet');
        }
        catch (MealDeadlinePassedException $e) {
            return $this->userFriendlyError(410, 'De deadline voor aanmelding voor deze maaltijd is al verstreken');
        }

        return $this->setPageContent(view('confirm/confirm', ['registration' => $registration]));
    }
}
