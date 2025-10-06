<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <p>Thank you for subscribing!</p>
    <p>Click the link below to confirm your subscription:</p>
    <a href="{{ url('/subscription/verify/'.$subscription->verification_token) }}">Confirm Subscription</a>
</body>
</html>
