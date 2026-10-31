<!DOCTYPE html>
<html>
<head>
    <title>Subscription Activated</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;">
        <h2 style="color: #28a745;">Subscription Activated!</h2>
        <p>Hello {{ $user->username }},</p>
        <p>Your subscription for the <strong>{{ $plan->name }}</strong> plan has been successfully activated.</p>
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
                <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Amount Paid:</strong></td>
                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ showAmount($plan->price) }} {{ gs('cur_text') }}</td>
            </tr>
        </table>
        <p style="margin-top: 30px;">Thank you for your support!<br>The {{ gs('site_name') }} Team</p>
    </div>
</body>
</html>
