<?php
session_start();

// Restrict access to admin users only
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'db_connection.php'; // Connect to the database

// Function to log admin activity
function logAdminActivity($action) {
    $logMessage = date('Y-m-d H:i:s') . " - Admin ID: {$_SESSION['admin_id']} - Action: $action" . PHP_EOL;
    file_put_contents('admin_activity.log', $logMessage, FILE_APPEND);
}

// Log dashboard access
logAdminActivity('Accessed Dashboard');

// Fetch dashboard statistics
try {
    $userCount = $conn->query("SELECT COUNT(*) FROM login")->fetchColumn();
    $productCount = $conn->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $orderCount = $conn->query("SELECT COUNT(*) FROM orders")->fetchColumn();
} catch (PDOException $e) {
    file_put_contents('error.log', date('Y-m-d H:i:s') . " - Database Error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
    die("An error occurred. Please try again later.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* General page styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        /* Dashboard header */
        h1 {
            background-color: #007bff;
            color: white;
            padding: 15px;
            margin: 0;
        }

        /* Statistics boxes */
        p {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            background-color: white;
            padding: 10px;
            margin: 10px auto;
            width: 50%;
            border-radius: 5px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Logout button */
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #dc3545;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }

        a:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <h1>Welcome, Admin <?php echo $_SESSION['username']; ?>!</h1>
    <p>Total Users: <?php echo $userCount; ?></p>
    <p>Total Products: <?php echo $productCount; ?></p>
    <p>Total Orders: <?php echo $orderCount; ?></p>
   
    <a href="logout.php">Logout</a>
</body>
</html>
