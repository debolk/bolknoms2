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
        Mail::send('front/email', ['name' => $name, 'registrations' => $registrations], function($mail){
            $mail->to("$name <$email>");
            $mail->from('no-reply@debolk.nl');
            $mail->subject('[deBolk] Aanmelding eettafel');
            $mail->reply_to(Config::get('app.email.reply_to'));
        });
    }
}