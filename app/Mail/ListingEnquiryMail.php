<?php

namespace App\Mail;

use App\Models\Listing;
use Illuminate\Mail\Mailable;

class ListingEnquiryMail extends Mailable
{
    public function __construct(
        public Listing $listing,
        public array $payload
    ) {
    }

    public function build(): self
    {
        return $this->subject('New listing enquiry: '.$this->listing->title)
            ->view('emails.listing-enquiry');
    }
}
