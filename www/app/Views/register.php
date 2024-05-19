<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
<h2>Register</h2>
<?php if (isset($error)): ?>
    <p style="color:red;"><?= $error ?></p>
<?php endif; ?>
<form method="post" action="<?= base_url('auth/register_post') ?>">
    <label>First Name:</label><br>
    <input type="text" name="first_name"><br>
    <label>Last Name:</label><br>
    <input type="text" name="last_name"><br>
    <label>Email:</label><br>
    <input type="email" name="email"><br>
    <label>Phone:</label><br>
    <input type="text" name="phone"><br>
    <label>Password:</label><br>
    <input type="password" name="password"><br>
    <input type="submit" value="Register">
</form>
</body>
</html>
