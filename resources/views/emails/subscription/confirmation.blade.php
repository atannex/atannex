<!DOCTYPE html>
<html>
<head>
    <title>Subscription Confirmed</title>
</head>
<body>
    <p>Hello {{ $name }},</p>
    <p>Thank you for confirming your subscription to {{ config('app.name') }}!</p>
    <p>You will now receive updates and newsletters to {{ $email }}.</p>
    <p>Best regards,<br>{{ config('app.name') }} Team</p>
</body>
</html>
