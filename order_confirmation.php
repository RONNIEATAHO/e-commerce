<?php
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'] ?? 'Customer';
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
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
        }
        .confirmation-message h1 {
            color: #28a745;
            margin-bottom: 10px;
        }
        .confirmation-message p {
            color: #333;
            font-size: 1.1em;
        }
        .confirmation-message a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 16px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
        .confirmation-message a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="confirmation-message">
        <h1>Order Confirmed!</h1>
        <p>Thank you, <?php echo htmlspecialchars($username); ?>. Your order has been placed and will be delivered shortly.</p>
        <a href="beverages.php">Back to Beverages</a>
    </div>
</body>
</html>
