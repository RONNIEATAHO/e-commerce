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
    <title>Order Confirmation - ShopSmart</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            justify-content: center;
            align-items: center;
            background-image: linear-gradient(to right, #e0f7fa, #ffffff);
        }

        .confirmation-message {
            background-color: #ffffff;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 500px;
            width: 90%;
            animation: fadeIn 0.8s ease-in-out;
        }

        .confirmation-message h1 {
            color: #28a745;
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .confirmation-message p {
            font-size: 1.2em;
            color: #555;
            margin-bottom: 25px;
        }

        .confirmation-message a {
            display: inline-block;
            padding: 12px 25px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .confirmation-message a:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 600px) {
            .confirmation-message {
                padding: 25px 20px;
            }

            .confirmation-message h1 {
                font-size: 2em;
            }

            .confirmation-message p {
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
    <div class="confirmation-message">
        <h1>✅ Order Confirmed!</h1>
        <p>Thank you for shopping with us. Your order is on its way and will be delivered soon.</p>
        <a href="beverages.php">🛒 Back to Beverages</a>
    </div>
</body>
</html>
