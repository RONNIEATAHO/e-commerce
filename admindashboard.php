<?php
session_start();

// ========== SECURITY CHECK ==========
if (!isAdminLoggedIn()) {
    redirectToLogin();
}

// ========== DATABASE CONNECTION ==========
include 'db_connection.php';

// ========== LOGGING ==========
logAdminActivity('Accessed Dashboard');

// ========== FETCH DASHBOARD STATS ==========
try {
    $userCount = getRowCount($conn, 'login');
    $productCount = getRowCount($conn, 'products');
    $orderCount = getRowCount($conn, 'orders');
} catch (PDOException $e) {
    logError("Database Error: " . $e->getMessage());
    die("An error occurred. Please try again later.");
}

// ========== MODULAR FUNCTIONS BELOW ==========

function isAdminLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['role'] === 'admin';
}

function redirectToLogin() {
    header("Location: login.php");
    exit();
}

function logAdminActivity($action) {
    $logMessage = date('Y-m-d H:i:s') . " - Admin ID: {$_SESSION['admin_id']} - Action: $action" . PHP_EOL;
    file_put_contents('admin_activity.log', $logMessage, FILE_APPEND);
}

function getRowCount($conn, $table) {
    return $conn->query("SELECT COUNT(*) FROM $table")->fetchColumn();
}

function logError($message) {
    file_put_contents('error.log', date('Y-m-d H:i:s') . " - $message" . PHP_EOL, FILE_APPEND);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        h1 {
            background-color: #007bff;
            color: white;
            padding: 15px;
            margin: 0;
        }

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
    <h1>Welcome, Admin <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <p>Total Users: <?php echo $userCount; ?></p>
    <p>Total Products: <?php echo $productCount; ?></p>
    <p>Total Orders: <?php echo $orderCount; ?></p>
   
    <a href="logout.php">Logout</a>
</body>
</html>
