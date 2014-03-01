<?php

class MailerBug
{
    /**
     * Sends a notification of an error occurred
     * @return void
     */
    public static function send_bug_notification($code, $message)
    {
        Mail::send('error/email', ['code' => $code, 'error_message' => $message], function($mail)
        {
            $mail->to(Config::get('app.email.admin', 'ICTcom'));
            $mail->from('no-reply@debolk.nl', 'De Bolk');
            $mail->subject('[deBolk] Foutmelding bolknoms');
        });
    }
}