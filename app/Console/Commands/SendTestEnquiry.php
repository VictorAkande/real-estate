<?php

namespace App\Console\Commands;

use App\Mail\ListingEnquiryMail;
use App\Models\Listing;
use App\Models\Location;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendTestEnquiry extends Command
{
    protected $signature = 'send:test-enquiry {email}';
    protected $description = 'Send a test listing enquiry email.';

    public function handle(): int
    {
        $email = $this->argument('email');

        $location = Location::first();
        if (! $location) {
            $location = Location::create([
                'name' => 'Lagos',
                'state' => 'Lagos',
                'slug' => 'lagos',
            ]);
        }

        $listing = Listing::first();
        if (! $listing) {
            $title = 'Test Listing';
            $listing = Listing::create([
                'title' => $title,
                'slug' => Str::slug($title.'-'.Str::random(4)),
                'listing_type' => 'sale',
                'property_type' => 'Apartment',
                'price' => 1000000,
                'bedrooms' => 3,
                'bathrooms' => 3,
                'toilets' => 3,
                'parking_spaces' => 2,
                'area_sqm' => 120,
                'address' => 'Victoria Island, Lagos',
                'location_id' => $location->id,
                'status' => 'active',
                'featured' => false,
            ]);
        }

        $payload = [
            'name' => 'Test Sender',
            'email' => 'test@sender.com',
            'message' => 'Test enquiry from the listing detail page.',
        ];

        Mail::to($email)->send(new ListingEnquiryMail($listing, $payload));

        $this->info('Test enquiry sent to '.$email);

        return self::SUCCESS;
    }
}
