<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Mail\ContactMailConfirmation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $recipient = config('mail.enquiry_to') ?: config('mail.from.address');

        if ($recipient) {
            Mail::to($recipient)->send(new ContactMail($data));
        }

        Mail::to($data['email'])->send(new ContactMailConfirmation($data));

        return redirect()->route('contact.thankyou');
    }
}
