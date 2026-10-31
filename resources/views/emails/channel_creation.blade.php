<!DOCTYPE html>
<html>
<head>
    <title>Channel Created Successfully</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;">
        <h2 style="color: #ff4500;">Your Channel is Ready!</h2>
        <p>Hello {{ $user->username }},</p>
        <p>Congratulations! Your channel <strong>{{ $channelName }}</strong> has been successfully created on {{ gs('site_name') }}.</p>
        <p>You can now start uploading videos, creating reels, and building your audience.</p>
        <p style="margin-top: 20px;">
            <a href="{{ route('studio.dashboard') }}" style="background-color: #ff4500; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Go to Creator Studio</a>
        </p>
        <p style="margin-top: 30px;">Happy creating!<br>The {{ gs('site_name') }} Team</p>
    </div>
</body>
</html>
