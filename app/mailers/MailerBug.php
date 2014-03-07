<?php

class MailerBug
{
    /**
     * Sends a notification of an error occurred
     * @param  string $log_entry the entry that was logged in the log
     * @return void
     */
    public static function send_bug_notification($log_entry)
    {
        Mail::send('error/email', ['log_entry' => $log_entry], function($mail)
        {
            $mail->to(Config::get('app.email.admin', 'ICTcom'));
            $mail->from('no-reply@debolk.nl', 'De Bolk');
            $mail->subject('[deBolk] Foutmelding bolknoms');
        });
    }
}