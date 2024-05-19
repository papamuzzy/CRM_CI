<!DOCTYPE html>
<html>
<head>
    <title>Payment Options</title>
</head>
<body>
<h2>Select Payment Option</h2>
<form method="post" action="<?= base_url('auth/payment') ?>">
    <!-- Добавьте поля для данных оплаты -->
    <label>Payment Option:</label><br>
    <select name="payment_option">
        <option value="credit_card">Credit Card</option>
        <option value="paypal">PayPal</option>
    </select><br>
    <label>Card Number:</label><br>
    <input type="text" name="card_number"><br>
    <!-- Другие поля -->
    <input type="submit" value="Submit Payment">
</form>
</body>
</html>
