<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
</head>
<body>
<h2>Welcome to our site</h2>
<button onclick="window.location.href='<?php echo base_url('auth/register'); ?>'">Register</button>
<button onclick="window.location.href='<?php echo base_url('auth/login'); ?>'">Login</button>
</body>
</html>
