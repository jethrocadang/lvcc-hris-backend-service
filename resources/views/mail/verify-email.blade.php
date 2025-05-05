<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
    <style>
        table, td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        a {
            color: #ffffff;
            text-decoration: none;
        }
    </style>
</head>

<body style="margin:0; padding:0; background-color:#f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                       style="max-width: 600px; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
                    <tr>
                        <td style="padding: 20px; background-color: #2d89ef; text-align: center;">
                            {{-- Brand logo --}}
                            <img src="{{ asset('storage/brand/logo.png') }}" alt="Brand Logo" height="40" style="margin-bottom: 10px;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px;">La Verdad Christian College Inc.</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 30px;">
                            <h2 style="color: #333333; margin-top: 0;">Hello, {{ $applicant->first_name }}!</h2>
                            <p style="font-size: 16px; color: #555555;">
                                Thank you for registering. Please verify your email by clicking the button below:
                            </p>
                            <p style="text-align: center; margin: 30px 0;">
                                <a href="{{ $verifyEmailUrl }}"
                                   style="display: inline-block; padding: 12px 25px; background-color: #2d89ef; color: #ffffff; border-radius: 5px; font-size: 16px;">
                                    Verify Email
                                </a>
                            </p>
                            <p style="font-size: 14px; color: #999999;">
                                If you did not register, you can safely ignore this email.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 20px; text-align: center; background-color: #f9f9f9; font-size: 12px; color: #aaaaaa;">
                            &copy; {{ now()->year }} [Your Company]. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
