<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
</head>
<body>
    <h2>Hello {{ $user->name }}</h2>
    <p>Click the button below to verify your email:</p>
  <a href="{{ route('custom.verify', $user->id) }}">Verify Email</a>


    <p>If you didn’t create an account, you can ignore this email.</p>
</body>
</html>
