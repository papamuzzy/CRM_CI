<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
<h2>Login</h2>
<?php if (isset($error)): ?>
    <p style="color:red;"><?= $error ?></p>
<?php endif; ?>
<form method="post" action="<?= base_url('auth/login_post') ?>">
    <label>Email:</label><br>
    <input type="email" name="email"><br>
    <label>Password:</label><br>
    <input type="password" name="password"><br>
    <input type="submit" value="Login">
</form>
<p><a href="<?= base_url('auth/request_password_reset') ?>">Forgot Password?</a></p>
</body>
</html>
