<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Services\ConfirmRegistrationService;
use App\Services\MealDeadlinePassedException;
use App\Services\SaltMismatchException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class Confirm extends Controller
{
    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Contracts\View\View
     */
    public function confirm(string $id, string $salt)
    {
        $registration = Registration::where('id', $id)->first();
        if (! $registration) {
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
