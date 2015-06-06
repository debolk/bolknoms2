<?php

namespace App\Http\Helpers;

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
    public static function confirmationEmail($registration)
    {
        \Mail::send('mails/confirmRegistration', ['registration' => $registration], function($message) use ($registration) {
            $message->to($registration->email, $registration->name);
            $message->subject("Bevestigen aanmelding maaltijd". $registration->longDate());
        });
    }
}
