<!DOCTYPE html>
<html>
<head>
    <title>Marketplace Plan Activated</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;">
        <h2 style="color: #ff4500;">Marketplace Plan Activated!</h2>
        <p>Hello <?php echo e($user->name); ?>,</p>
        <p>Your marketplace plan <strong><?php echo e($plan->plan_name); ?></strong> has been successfully activated.</p>
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Plan Name:</strong></td>
                <td style="padding: 10px; border-bottom: 1px solid #eee;"><?php echo e($plan->plan_name); ?></td>
            </tr>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Price:</strong></td>
                <td style="padding: 10px; border-bottom: 1px solid #eee;"><?php echo e(showAmount($plan->plan_price)); ?> <?php echo e(gs('cur_text')); ?></td>
            </tr>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Duration:</strong></td>
                <td style="padding: 10px; border-bottom: 1px solid #eee;"><?php echo e($plan->plan_duration); ?> Days</td>
            </tr>
        </table>
        <p style="margin-top: 20px;">
            <a href="<?php echo e(route('marketplace.dashboard')); ?>" style="background-color: #ff4500; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Go to Marketplace Dashboard</a>
        </p>
        <p style="margin-top: 30px;">Best regards,<br>The <?php echo e(gs('site_name')); ?> Team</p>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\emails\marketplace_plan.blade.php ENDPATH**/ ?>