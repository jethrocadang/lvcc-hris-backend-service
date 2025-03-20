<!DOCTYPE html>
<html>
<head>
    <title>Access Portal</title>
</head>
<body>
    <h2>Hello, {{ $applicant->first_name }}!</h2>
    <p>Congratulations! You are now verified. Please click the link below to access portal:</p>
    <p>
        <a href="{{ $portalAccessUrl }}">Portal Access</a>
    </p>
    <p>If you did not register, please ignore this email.</p>
</body>
</html>
