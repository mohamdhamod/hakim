<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterContinueMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $continueUrl;

    public function __construct(string $continueUrl)
    {
        $this->continueUrl = $continueUrl;
    }

    public function build(): self
    {
        return $this
            ->subject(__('translation.auth.continue_registration_email_subject'))
            ->view('emails.auth.register_continue');
    }
}
