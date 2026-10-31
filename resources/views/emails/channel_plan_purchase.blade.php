<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Channel Membership Activated</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; line-height: 1.6; color: #1e293b; background-color: #f8fafc; margin: 0; padding: 24px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        
        <!-- Header Banner -->
        <div style="background: linear-gradient(135deg, #ff5200 0%, #f97316 50%, #f59e0b 100%); padding: 28px 24px; text-align: center; color: #ffffff;">
            <div style="display: inline-block; background: rgba(255,255,255,0.2); padding: 4px 12px; border-radius: 999px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">
                👑 Channel Membership
            </div>
            <h1 style="margin: 0; font-size: 22px; font-weight: 800; color: #ffffff;">Membership Activated!</h1>
            <p style="margin: 6px 0 0 0; font-size: 13px; opacity: 0.95; color: #ffffff;">Welcome to the exclusive community of {{ $channel->name ?? 'Creator' }}</p>
        </div>

        <!-- Body Content -->
        <div style="padding: 28px 24px;">
            <p style="font-size: 15px; margin-top: 0;">Hello <strong>{{ $user->firstname ? ($user->firstname . ' ' . $user->lastname) : ($user->username ?? 'Subscriber') }}</strong>,</p>
            <p style="font-size: 14px; color: #475569; margin-bottom: 24px;">
                Your channel membership for <strong>{{ $channel->name ?? 'the creator' }}</strong> (<strong>{{ $plan->name ?: ($plan->duration_days . ' Days') }}</strong>) has been successfully activated. You now have unlimited access to all exclusive premium videos published by this creator!
            </p>

            <!-- Details Card -->
            <div style="background: #f8fafc; border: 1px solid #edf2f7; border-radius: 12px; padding: 18px 20px; margin-bottom: 26px;">
                <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                    <tr>
                        <td style="padding: 8px 0; color: #64748b; border-bottom: 1px solid #edf2f7;"><strong>Channel:</strong></td>
                        <td style="padding: 8px 0; font-weight: 700; color: #0f172a; text-align: right; border-bottom: 1px solid #edf2f7;">{{ $channel->name ?? 'Creator' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #64748b; border-bottom: 1px solid #edf2f7;"><strong>Membership Plan:</strong></td>
                        <td style="padding: 8px 0; font-weight: 700; color: #ff5200; text-align: right; border-bottom: 1px solid #edf2f7;">{{ $plan->name ?: ($plan->duration_days . ' Days') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #64748b; border-bottom: 1px solid #edf2f7;"><strong>Duration:</strong></td>
                        <td style="padding: 8px 0; font-weight: 700; color: #0f172a; text-align: right; border-bottom: 1px solid #edf2f7;">{{ $plan->duration_days }} Days</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #64748b; border-bottom: 1px solid #edf2f7;"><strong>Amount Paid:</strong></td>
                        <td style="padding: 8px 0; font-weight: 700; color: #0f172a; text-align: right; border-bottom: 1px solid #edf2f7;">
                            @if((float)$plan->price > 0)
                                {{ showAmount($plan->price) }} {{ gs('cur_text') ?? 'INR' }}
                            @else
                                Free
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #64748b; border-bottom: 1px solid #edf2f7;"><strong>Transaction ID:</strong></td>
                        <td style="padding: 8px 0; font-family: monospace; font-size: 12px; color: #0f172a; text-align: right; border-bottom: 1px solid #edf2f7;">{{ $trx }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #64748b;"><strong>Valid Until:</strong></td>
                        <td style="padding: 8px 0; font-weight: 700; color: #16a34a; text-align: right;">
                            {{ $subscription && $subscription->expires_at ? $subscription->expires_at->format('M d, Y h:i A') : now()->addDays($plan->duration_days)->format('M d, Y') }}
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Call to Action -->
            <div style="text-align: center; margin: 28px 0;">
                @php
                    $channelPremiumUrl = $channel ? url('/channels/' . $channel->slug . '/premium') : url('/');
                @endphp
                <a href="{{ $channelPremiumUrl }}" 
                   style="display: inline-block; background: linear-gradient(135deg, #ff5200 0%, #f97316 100%); color: #ffffff; padding: 12px 28px; text-decoration: none; border-radius: 999px; font-weight: 700; font-size: 14px; box-shadow: 0 4px 10px rgba(249, 115, 22, 0.3);">
                    Watch Exclusive Videos →
                </a>
            </div>

            <p style="font-size: 12px; color: #94a3b8; text-align: center; margin-top: 24px; border-top: 1px solid #f1f5f9; padding-top: 16px;">
                Thank you for supporting <strong>{{ $channel->name ?? 'the creator' }}</strong> on {{ gs('site_name') ?? 'Binteo' }}.<br>
                If you have any questions, feel free to contact our support team.
            </p>
        </div>

    </div>
</body>
</html>
