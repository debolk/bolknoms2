<?php

class MailerRegistration
{
    /**
     * Sends a confirmation email for a given set of registrations
     * @param array(Registration) $registrations
     * @return void
     */
    public static function send_confirmation($name, $email, $registrations)
    {
        Mail::send('front/email', ['name' => $name, 'registrations' => $registrations], function($mail){
            $mail->to("$name <$email>");
            $mail->from('no-reply@debolk.nl');
            $mail->subject('[deBolk] Aanmelding eettafel');
            $mail->reply_to(Config::get('app.email.reply_to'));
        });
    }
}