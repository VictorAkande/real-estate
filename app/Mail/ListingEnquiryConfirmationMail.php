<?php

namespace App\Mail;

use App\Models\Listing;
use Illuminate\Mail\Mailable;

class ListingEnquiryConfirmationMail extends Mailable
{
    public function __construct(
        public Listing $listing,
        public array $payload
    ) {
    }

    public function build(): self
    {
        return $this->subject('We received your enquiry')
            ->view('emails.listing-enquiry-confirmation');
    }
}
