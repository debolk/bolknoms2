<?php

namespace App\Http\Helpers;

use Mail;

/**
 * Sends various e-mails to use lifecycle events
 */
class Mailer
{
    /**
     * Sends an e-mail to request confirmation for
     * @param  \App\Models\Registration $registration
     * @return void
     */
    public static function confirmationEmail(App\Models\Registration $registration)
    {
        Mail::send(['mails/confirmRegistration/html', 'mails/confirmRegistration/text'], [
            'registration' => $registration
        ], function($message) use ($registration) {
            $message->to($registration->email, $registration->name);
            $message->subject("Bevestigen aanmelding maaltijd ". $registration->longDate());
        });
    }

    /**
     * Sends an e-mail to all present registrations that a meal has been cancelled
     * @param  App\Models\Meal $meal
     * @return void
     */
    public static function mealIsDestroyedEmail(App\Models\Meal $meal)
    {
        foreach ($meals->registrations as $registration) {
            Mail::send(['mails/mealIsDestroyed/html', 'mails/mealIsDestroyed/text'], [
                'meal'         => $meal,
                'registration' => $meal->registration,
            ], function($message) use ($meal, $registration) {
                $message->to($registration->email, $registration->name);
                $message->subject("Maaltijd ". $meal->longDate() . 'gaat niet door');
            });
        }
    }
}
