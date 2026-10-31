<!DOCTYPE html>
<html>
<head>
    <title>Welcome to <?php echo e(gs('site_name')); ?></title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;">
        <h2 style="color: #ff4500;">Welcome, <?php echo e($user->firstname); ?>!</h2>
        <p>Thank you for joining <strong><?php echo e(gs('site_name')); ?></strong>. We are excited to have you as a member of our community.</p>
        <p>Start exploring videos, subscribing to your favorite channels, and sharing your thoughts.</p>
        <p style="margin-top: 20px;">
            <a href="<?php echo e(url('/')); ?>" style="background-color: #ff4500; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Start Watching</a>
        </p>
        <p style="margin-top: 30px;">Best regards,<br>The <?php echo e(gs('site_name')); ?> Team</p>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\emails\user_welcome.blade.php ENDPATH**/ ?>