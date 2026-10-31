<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Binteo Marketplace</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;">
        <h2 style="color: #ff4500;">Hello, <?php echo e($user->name); ?>!</h2>
        <p>Welcome to <strong><?php echo e(gs('site_name')); ?> Marketplace</strong>. Your talent account has been successfully registered.</p>
        <p>You can now log in and complete your portfolio to showcase your skills to the world.</p>
        <p style="margin-top: 20px;">
            <a href="<?php echo e(route('marketplace.login')); ?>" style="background-color: #ff4500; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Login to Dashboard</a>
        </p>
        <p style="margin-top: 30px;">Best regards,<br>The <?php echo e(gs('site_name')); ?> Team</p>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\emails\marketplace_welcome.blade.php ENDPATH**/ ?>