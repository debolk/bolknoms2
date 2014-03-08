<?php

class MailerRegistration
{
    /**
     * Sends a confirmation email for a given set of registrations
     * @param string $name the name of the registered user
     * @param string $email the e-mail address to sent the confirmation to
     * @param array(Registration) $registrations
     * @return Mail
     */
    public static function send_confirmation($name, $email, $registrations)
    {
        Mail::send('register/email', ['name' => $name, 'registrations' => $registrations], function($mail) use ($name, $email)
        {
            $mail->to($email, $name);
            $mail->from('no-reply@debolk.nl', 'De Bolk');
            $mail->subject('[deBolk] Aanmelding eettafel');
            $mail->replyTo(Config::get('app.email.reply_to.email'), Config::get('app.email.reply_to.name'));
        });
    }
}