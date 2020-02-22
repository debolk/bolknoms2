<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MealDestroyed extends Mailable
{
    use Queueable;
    use SerializesModels;

    private $meal;
    private $registration;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($meal, $registration)
    {
        $this->meal = $meal;
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
        $this->replyTo(config('mail.reply-to.mail'), config('mail.reply-to.name'));
        $this->subject("Maaltijd " . $this->meal->longDate() . ' gaat niet door');

        $data = ['registration' => $this->registration, 'meal' => $this->meal];
        return $this->view('mails/meal_is_destroyed/html', $data)
                    ->text('mails/meal_is_destroyed/text', $data);
    }
}
