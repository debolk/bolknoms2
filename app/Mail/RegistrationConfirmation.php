<?php

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationConfirmation extends Mailable
{
    use Queueable;
    use SerializesModels;

    private $registration;

    public function __construct(Registration $registration)
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
        return $this->to($this->registration->email, $this->registration->name)
                    ->replyTo(config('mail.reply-to.mail'), config('mail.reply-to.name'));
                    ->subject("Bevestigen aanmelding maaltijd " . $this->registration->longDate());
                    ->view('mails/confirm_registration/html', ['registration' => $this->registration])
                    ->text('mails/confirm_registration/text', ['registration' => $this->registration]);
    }
}
