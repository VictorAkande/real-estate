<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class ContactMailConfirmation extends Mailable
{
    public function __construct(
        public array $payload
    ) {
    }

    public function build(): self
    {
        return $this->subject('We received your message')
            ->view('emails.contact-confirmation');
    }
}
