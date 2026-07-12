<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>New contact message</title>
    </head>
    <body>
        <h2>New contact form message</h2>
        <p><strong>Name:</strong> {{ $payload['name'] }}</p>
        <p><strong>Email:</strong> {{ $payload['email'] }}</p>
        <hr>
        <p><strong>Message:</strong></p>
        <p>{{ $payload['message'] }}</p>
    </body>
</html>
