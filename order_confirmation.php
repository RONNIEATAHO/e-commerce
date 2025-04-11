<?php
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .confirmation-message {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .confirmation-message h1 {
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="confirmation-message">
        <h1>Order Confirmed!</h1>
        <p>Thank you for your order. It will be delivered shortly.</p>
        <a href="beverages.php">Back to Beverages</a>
    </div>
</body>
</html>