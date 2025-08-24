<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user; // User info

    public function __construct($user)
    {
      
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Welcome to Brickly Solutions!')
                    ->view('emails.registration_success');
    }
}
