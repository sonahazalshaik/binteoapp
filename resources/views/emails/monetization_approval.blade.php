<!DOCTYPE html>
<html>
<head>
    <title>Monetization Approved</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;">
        <h2 style="color: #28a745;">Congratulations!</h2>
        <p>Hello {{ $user->username }},</p>
        <p>We are pleased to inform you that your channel's <strong>Monetization Request</strong> has been approved!</p>
        <p>You can now start earning from your content through ad revenue and other monetization features.</p>
        <p style="margin-top: 20px;">
            <a href="{{ route('studio.monetization') }}" style="background-color: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">View Monetization Dashboard</a>
        </p>
        <p style="margin-top: 30px;">Keep up the great work!<br>The {{ gs('site_name') }} Team</p>
    </div>
</body>
</html>
