<?php
session_start();
include 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch orders
$orders = [];
$error = "";

try {
    $stmt = $conn->prepare("
        SELECT o.order_id, o.order_date, o.status, o.total_amount, l.username
        FROM orders o
        JOIN login l ON o.user_id = l.id
        ORDER BY o.order_date DESC
    ");
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = "Failed to load orders: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            margin: 0;
            padding: 0;
        }

        nav {
            background-color: #343a40;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav h1 {
            color: white;
            margin: 0;
        }

        .back-btn {
            background-color: #ffc107;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            color: #212529;
            font-weight: bold;
        }

        .container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 20px;
        }

        .order-item {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .order-info h2 {
            margin: 0 0 10px;
        }

        .order-info p {
            margin: 5px 0;
        }

        .empty-message {
            text-align: center;
            margin-top: 50px;
            color: #666;
        }

        .error-message {
            color: red;
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<nav>
    <h1>View Orders</h1>
    <a href="manager_dashboard.php" class="back-btn">← Back to Dashboard</a>
</nav>

<div class="container">
    <?php if ($error): ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (empty($orders)): ?>
        <p class="empty-message">No orders have been placed yet.</p>
    <?php else: ?>
        <div id="orders-list">
            <?php foreach ($orders as $order): ?>
                <div class="order-item">
                    <div class="order-info">
                        <h2>Order #<?= htmlspecialchars($order['order_id']) ?></h2>
                        <p><strong>Customer:</strong> <?= htmlspecialchars($order['username']) ?></p>
                        <p><strong>Order Date:</strong> <?= htmlspecialchars($order['order_date']) ?></p>
                        <p><strong>Status:</strong> <?= htmlspecialchars($order['status']) ?></p>
                        <p><strong>Total Amount:</strong> $<?= number_format($order['total_amount'], 2) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
