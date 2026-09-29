<!DOCTYPE html>
<html>
<head>
    <title>Account Deletion Completed - {{ gs('site_name') }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;">
        <h2 style="color: #ff4500;">Hello, {{ $userName }}</h2>
        <p>This is to confirm that your request to delete your account on <strong>{{ gs('site_name') }}</strong> has been approved and completed by the administrator.</p>
        <p>As requested, all your channel information, uploaded videos, reels, comments, and personal settings have been permanently deleted from our servers.</p>
        <p><strong>Reason for termination:</strong> {{ $reason }}</p>
        <p>Thank you for the time you spent with us. If this deletion was an error, or if you wish to return, you can register a new account at any time.</p>
        <p style="margin-top: 30px;">Best regards,<br>The {{ gs('site_name') }} Team</p>
    </div>
</body>
</html>
