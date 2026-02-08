<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Listing enquiry</title>
    </head>
    <body>
        <h2>New listing enquiry</h2>
        <p><strong>Listing:</strong> {{ $listing->title }}</p>
        <p><strong>Location:</strong> {{ $listing->address }} · {{ $listing->location->name ?? 'Nigeria' }}</p>
        <hr>
        <p><strong>Name:</strong> {{ $payload['name'] }}</p>
        <p><strong>Email:</strong> {{ $payload['email'] }}</p>
        <p><strong>Message:</strong></p>
        <p>{{ $payload['message'] }}</p>
    </body>
</html>
