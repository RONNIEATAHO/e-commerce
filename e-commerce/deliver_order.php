<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'waiter') {
    header('Location: login.php');
    exit();
}
include 'db_connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .order {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .order h2 {
            margin-top: 0;
            color: #555;
        }
        .order p {
            margin: 5px 0;
            color: #777;
        }
        .order ul {
            list-style-type: none;
            padding: 0;
        }
        .order ul li {
            padding: 5px 0;
            border-bottom: 1px solid #ddd;
        }
        .order ul li:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <h1>Orders for Delivery</h1>
    <?php if (empty($orders)): ?>
        <p>No orders found.</p>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <div class="order">
                <h2>Order #<?php echo htmlspecialchars($order['id']); ?></h2>
                <p><strong>Customer:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
                <p><strong>Items:</strong></p>
                <ul>
                    <?php
                    $items = json_decode($order['items'], true);
                    foreach ($items as $item): ?>
                        <li><?php echo htmlspecialchars($item['name']); ?> - $<?php echo htmlspecialchars($item['price']); ?></li>
                    <?php endforeach; ?>
                </ul>
                <p><strong>Total:</strong> $<?php echo htmlspecialchars($order['total_price']); ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>