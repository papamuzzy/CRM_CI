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
<form method="post" action="<?= base_url('auth/send_password_reset_email') ?>">
    <label>Email:</label><br>
    <input type="email" name="email"><br>
    <input type="submit" value="Send Reset Link">
</form>
</body>
</html>
