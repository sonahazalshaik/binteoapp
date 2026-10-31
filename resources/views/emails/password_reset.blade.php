<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { width: 80%; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; }
        .header { text-align: center; margin-bottom: 30px; }
        .button { display: inline-block; padding: 12px 24px; background-color: #ff4500; color: #ffffff !important; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .footer { margin-top: 30px; font-size: 0.8em; color: #777; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Password Reset Request</h2>
        </div>
        <p>Hello {{ $user->username }},</p>
        <p>You are receiving this email because we received a password reset request for your account on <strong>{{ gs('site_name') }}</strong>.</p>
        <p>Click the button below to reset your password. This link will expire in 60 minutes.</p>
        <p style="text-align: center; margin: 30px 0;">
            <a href="{{ $resetUrl }}" class="button">Reset Password</a>
        </p>
        <p>If you did not request a password reset, no further action is required.</p>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ gs('site_name') }}. All rights reserved.</p>
            <p>If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:<br>
            <a href="{{ $resetUrl }}">{{ $resetUrl }}</a></p>
        </div>
    </div>
</body>
</html>
