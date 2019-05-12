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
        $registration = Registration::where('id', $id)->first();
        if (!$registration) {
            return redirect('/')->with('action_result', [
                'status' => 'error',
                'message' => 'Deze aanmelding bestaat niet. Het kan zijn dat deze al weer verwijderd is.',
            ]);
        }

        // Confirm registration
        try {
            $confirm = with(new ConfirmRegistrationService($registration, $salt))->execute();
        } catch (SaltMismatchException $e) {
            return redirect('/')->with('action_result', [
                'status' => 'error',
                'message' => 'De beveiligingscode klopt niet. Gebruik de link in de e-mail.',
            ]);
        } catch (MealDeadlinePassedException $e) {
            return redirect('/')->with('action_result', [
                'status' => 'error',
                'message' => 'De deadline voor aanmelding voor deze maaltijd is al verstreken. Je kunt je aanmelding niet
                meer bevestigen. Je kunt helaas niet mee-eten.',
            ]);
        }

        return view('confirm/confirm', ['registration' => $registration]);
    }
}
