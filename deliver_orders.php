

<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'waiter') {
    header('Location: login.php');
    exit();
}

include 'db_connection.php';

// Fetch user profile photo
$user_id = $_SESSION['user_id'];  // Session user_id should match the 'id' in your 'login' table
$sql_user = "SELECT profile_photo FROM login WHERE id = :user_id";  // Use 'id' for user identification
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_user->execute();
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);
$profile_photo = $user['profile_photo'];

// Fetch filtered orders from the database (default to "Pending" orders)
$status_filter = 'Pending';  // Default filter
if (isset($_GET['status']) && in_array($_GET['status'], ['Pending', 'Shipped', 'Delivered', 'Cancelled'])) {
    $status_filter = $_GET['status'];
}

// Fetch orders and join with login table to get customer details
$sql = "
    SELECT o.order_id, o.user_id, o.order_date, o.status, o.total_amount, l.username as customer_name
    FROM orders o
    JOIN login l ON o.user_id = l.id
    WHERE o.status = :status
    ORDER BY o.order_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':status', $status_filter, PDO::PARAM_STR);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch order items (assuming there is a separate table for order items)
$order_items = [];
foreach ($orders as $order) {
    $order_id = $order['order_id'];
    $sql_items = "SELECT p.product_name AS item_name, oi.quantity, oi.price 
                  FROM order_items oi
                  JOIN products p ON oi.product_id = p.product_id
                  WHERE oi.order_id = :order_id";
    $stmt_items = $conn->prepare($sql_items);
    $stmt_items->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt_items->execute();
    $items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);
    $order_items[$order_id] = $items;
}

// Mark order as delivered
if (isset($_GET['deliver_id'])) {
    $deliver_id = $_GET['deliver_id'];
    $update_sql = "UPDATE orders SET status = 'Delivered' WHERE order_id = :order_id";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bindParam(':order_id', $deliver_id, PDO::PARAM_INT);
    $update_stmt->execute();
    header("Location: deliver_orders.php");
    exit();
}

// Delete order
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM orders WHERE order_id = :order_id";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bindParam(':order_id', $delete_id, PDO::PARAM_INT);
    $delete_stmt->execute();
    header("Location: deliver_orders.php");
    exit();
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Orders</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #f0f0f0; /* Default light text color */
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.6); /* Dark overlay for better contrast */
            z-index: -1;
        }

        .navbar {
            background-color: rgba(51, 51, 51, 0.9); /* Slightly transparent */
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        .navbar .logo {
            color: white;
            font-size: 20px;
            font-weight: bold;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            margin-left: 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .navbar a:hover {
            background-color: #575757;
        }

        .filter {
            margin: 20px;
            text-align: center;
        }

        .filter select {
            padding: 8px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
            background-color: #fff;
            color: #333;
        }

        h1 {
            text-align: center;
            margin: 20px;
            color: #fff;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
        }

        .order-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        .profile-section {
            text-align: center;
            margin-bottom: 20px;
            color: #fff;
        }

        .profile-section img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
            border: 3px solid #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        .profile-section p {
            font-size: 18px;
            font-weight: bold;
        }

        .orders-section {
            width: 80%;
            max-width: 800px;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            color: #333;
        }

        .order {
            margin-bottom: 25px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #4CAF50;
        }

        .order h2 {
            margin: 0 0 10px 0;
            color: #2c3e50;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .order p {
            font-size: 16px;
            color: #555;
            margin: 8px 0;
        }

        .order ul {
            list-style-type: none;
            padding-left: 0;
            margin: 15px 0;
        }

        .order li {
            font-size: 14px;
            color: #444;
            padding: 5px 0;
            border-bottom: 1px dashed #eee;
        }

        .order li:last-child {
            border-bottom: none;
        }

        .order button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .order button:hover {
            background-color: #3e8e41;
        }

        .order a {
            color: #f44336;
            text-decoration: none;
            margin-left: 10px;
            font-weight: bold;
            transition: color 0.3s;
        }

        .order a:hover {
            color: #d32f2f;
            text-decoration: underline;
        }

        .no-orders {
            text-align: center;
            font-size: 18px;
            color: #777;
            padding: 20px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .orders-section {
                width: 95%;
                padding: 15px;
            }
            
            .navbar {
                flex-direction: column;
                padding: 10px;
            }
            
            .navbar a {
                margin: 5px 0;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="logo">Restaurant Logo</div>
        <div>
            <a href="deliver_orders.php">Home</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <!-- Status Filter -->
    <div class="filter">
        <label for="status">Filter by Status: </label>
        <select name="status" id="status" onchange="window.location.href='deliver_orders.php?status=' + this.value">
            <option value="Pending" <?php if ($status_filter == 'Pending') echo 'selected'; ?>>Pending</option>
            <option value="Shipped" <?php if ($status_filter == 'Shipped') echo 'selected'; ?>>Shipped</option>
            <option value="Delivered" <?php if ($status_filter == 'Delivered') echo 'selected'; ?>>Delivered</option>
            <option value="Cancelled" <?php if ($status_filter == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
        </select>
    </div>

    <h1>Orders for Delivery</h1>

    <div class="order-container">
        <!-- Profile Section -->
        <div class="profile-section">
            <img src="uploads/<?php echo $profile_photo; ?>" alt="Profile Photo" class="profile-img">
            <p><strong>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</strong></p>
        </div>

        <!-- Orders Section -->
        <div class="orders-section">
            <?php if (empty($orders)): ?>
                <p class="no-orders">No orders found.</p>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <div class="order">
                        <h2>Order #<?php echo htmlspecialchars($order['order_id']); ?></h2>
                        <p><strong>Customer:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                        <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
                        <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>

                        <h3>Items:</h3>
                        <?php if (!empty($order_items[$order['order_id']])): ?>
                            <ul>
                                <?php foreach ($order_items[$order['order_id']] as $item): ?>
                                    <li><?php echo htmlspecialchars($item['item_name']) . " (x" . $item['quantity'] . ") - $" . number_format($item['price'], 2); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>No items found or invalid format.</p>
                        <?php endif; ?>

                        <p><strong>Total:</strong> $<?php echo number_format($order['total_amount'], 2); ?></p>

                        <?php if ($order['status'] == 'Pending'): ?>
                            <a href="?deliver_id=<?php echo $order['order_id']; ?>" onclick="return confirm('Are you sure you want to mark this order as delivered?')">
                                <button>Mark as Delivered</button>
                            </a>
                        <?php endif; ?>

                        <a href="?delete_id=<?php echo $order['order_id']; ?>" onclick="return confirm('Are you sure you want to delete this order?')">
                            <button>Delete Order</button>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>