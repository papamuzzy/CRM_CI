<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
<h2>Register</h2>
<?php if (isset($error) || isset($validation)): ?>
<div style="color:red;">
    <?= ((isset($validation)) ? $validation->listErrors() : $error) ?>
</div>
<?php endif; ?>
<form method="post">
    <label>Company name:</label><br>
    <input type="text" name="company" required><br>
    <label>First Name:</label><br>
    <input type="text" name="first_name" required><br>
    <label>Last Name:</label><br>
    <input type="text" name="last_name" required><br>
    <label>Email:</label><br>
    <input type="email" name="email" required><br>
    <input type="submit" value="Register">
</form>
</body>
</html>
