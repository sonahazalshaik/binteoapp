<!DOCTYPE html>
<html>
<head>
    <title>OTT Membership Activated</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;">
        <h2 style="color: #ff4500;">OTT Membership Activated!</h2>
        <p>Hello {{ $user->username }},</p>
        <p>Your <strong>{{ $plan->name }}</strong> OTT membership has been successfully activated. You now have access to premium OTT content.</p>
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Plan Name:</strong></td>
                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $plan->name }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Transaction ID:</strong></td>
                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $trx }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Price:</strong></td>
                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ showAmount($plan->price) }} {{ gs('cur_text') }}</td>
            </tr>
        </table>
        <p style="margin-top: 20px;">
            <a href="{{ route('user.ott-plans.index') }}" style="background-color: #ff4500; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Go to Mini OTT</a>
        </p>
        <p style="margin-top: 30px;">Enjoy your watching!<br>The {{ gs('site_name') }} Team</p>
    </div>
</body>
</html>
