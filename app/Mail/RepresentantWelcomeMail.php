<?php

// app/Mail/RepresentantWelcomeMail.php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RepresentantWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $representant;
    public $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($representant, $password)
    {
        $this->representant = $representant;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Bienvenue sur notre plateforme')
                    ->view('emails.representant_welcome')
                    ->with([
                        'email' => $this->representant->email,
                        'password' => $this->password,
                    ]);
    }
}

