<!DOCTYPE html>
<html>
<head>
    <title>Complete Registration</title>
</head>
<body>
<h2>Complete Registration</h2>
<?php if (isset($error)): ?>
    <p style="color:red;"><?= $error ?></p>
<?php endif; ?>
<form method="post" action="<?= base_url('auth/complete_registration_post') ?>">
    <input type="hidden" name="verification_code" value="<?= $verification_code ?>">
    <label>First Name:</label><br>
    <input type="text" name="first_name" value="<?= $first_name ?>" readonly><br>
    <label>Last Name:</label><br>
    <input type="text" name="last_name" value="<?= $last_name ?>" readonly><br>
    <label>Email:</label><br>
    <input type="email" name="email" value="<?= $email ?>" readonly><br>
    <label>Phone:</label><br>
    <input type="text" name="phone" required><br>
    <label>Password:</label><br>
    <input type="password" name="password" required><br>
    <label>Confirm Password:</label><br>
    <input type="password" name="confirm_password" required><br>
    <label>Payment Type:</label><br>
    <select name="payment_type">
        <option value="type1">Type 1</option>
        <option value="type2">Type 2</option>
        <!-- Add other payment types as needed -->
    </select><br>
    <input type="submit" value="Complete Registration">
</form>
</body>
</html>
