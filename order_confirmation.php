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
            background-image: url('background.jpg'); /* Set the correct image path */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .navbar {
            width: 100%;
            background-color: #2c3e50;
            color: white;
            padding: 15px 20px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar .logo {
            font-size: 24px;
            font-weight: bold;
        }

        .confirmation-message {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 450px;
            margin-top: 100px;
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
            padding: 10px 20px;
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
    <div class="navbar">
        <div class="logo">ORDER NOW</div>
    </div>
    <div class="confirmation-message">
        <h1>Order Confirmed!</h1>
        <p>Thank you, <?php echo htmlspecialchars($username); ?>. Your order has been placed and will be delivered shortly.</p>
        <a href="beverages.php">Back to products</a>
    </div>
</body>
</html>
