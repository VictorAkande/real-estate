<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Enquiry received</title>
    </head>
    <body>
        <h2>Thanks for your enquiry</h2>
        <p>We have received your request about:</p>
        <p><strong>{{ $listing->title }}</strong></p>
        <p>Our team will contact you shortly.</p>
        <hr>
        <p><strong>Your message:</strong></p>
        <p>{{ $payload['message'] }}</p>
    </body>
</html>
