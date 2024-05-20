<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
<h2>Reset Password</h2>
<?php if (isset($error)): ?>
    <p style="color:red;"><?= $error ?></p>
<?php endif; ?>
<form method="post" action="<?= base_url('auth/reset_password_post') ?>">
    <input type="hidden" name="verification_code" value="<?= $verification_code ?>">
    <label>Email:</label><br>
    <input type="email" name="email" value="<?= $email ?>" readonly><br>
    <label>New Password:</label><br>
    <input type="password" name="new_password"><br>
    <label>Confirm New Password:</label><br>
    <input type="password" name="confirm_password"><br>
    <input type="submit" value="Reset Password">
</form>
</body>
</html>
