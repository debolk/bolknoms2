<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegistrationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    private $registration;

    public function __construct($registration)
    {
        $this->registration = $registration;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->to($this->registration->email, $this->registration->name);
        $this->replyTo(env('MAIL_REPLY_TO_MAIL'), env('MAIL_REPLY_TO_NAME'));
        $this->subject("Bevestigen aanmelding maaltijd ". $this->registration->longDate());

        return $this->view('mails/confirm_registration/html', ['registration' => $this->registration])
                    ->text('mails/confirm_registration/text', ['registration' => $this->registration]);
    }
}
