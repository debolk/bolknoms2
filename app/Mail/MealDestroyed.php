<?php

namespace App\Mail;

use App\Models\Meal;
use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MealDestroyed extends Mailable
{
    use Queueable;
    use SerializesModels;

    private Meal $meal;
    private Registration $registration;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Meal $meal, Registration $registration)
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
        $data = ['registration' => $this->registration, 'meal' => $this->meal];

        return $this->to($this->registration->email, $this->registration->name);
                    ->replyTo(config('mail.reply-to.mail'), config('mail.reply-to.name'))
                    ->subject("Maaltijd " . $this->meal->longDate() . ' gaat niet door')
                    ->view('mails/meal_is_destroyed/html', $data)
                    ->text('mails/meal_is_destroyed/text', $data);
    }
}
