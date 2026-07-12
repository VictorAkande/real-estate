<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class ContactMail extends Mailable
{
    public function __construct(
        public array $payload
    ) {
    }

    public function build(): self
    {
        return $this->subject('New contact form message from '.$this->payload['name'])
            ->view('emails.contact');
    }
}
