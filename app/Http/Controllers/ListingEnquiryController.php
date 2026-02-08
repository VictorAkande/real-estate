<?php

namespace App\Http\Controllers;

use App\Mail\ListingEnquiryMail;
use App\Mail\ListingEnquiryConfirmationMail;
use App\Models\Listing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ListingEnquiryController extends Controller
{
    public function store(Request $request, Listing $listing): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $recipient = config('mail.enquiry_to') ?: config('mail.from.address');

        if ($recipient) {
            Mail::to($recipient)->send(new ListingEnquiryMail($listing, $data));
        }

        Mail::to($data['email'])->send(new ListingEnquiryConfirmationMail($listing, $data));

        return redirect()->route('listing.enquiry.thankyou', $listing);
    }
}
