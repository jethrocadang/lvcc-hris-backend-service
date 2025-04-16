<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
</head>
<body>
    <h2>Hello, {{ $applicant->first_name }}!</h2>
    <p>Thank you for registering. Please verify your email by clicking the link below:</p>
    <p>
        <a href="{{ $verifyEmailUrl }}">Verify Email</a>
    </p>
    <p>If you did not register, please ignore this email.</p>
</body>
</html>
