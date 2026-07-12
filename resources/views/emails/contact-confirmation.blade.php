<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Message received</title>
    </head>
    <body>
        <h2>Thanks for reaching out</h2>
        <p>We have received your message and our team will get back to you shortly.</p>
        <hr>
        <p><strong>Your message:</strong></p>
        <p>{{ $payload['message'] }}</p>
    </body>
</html>
