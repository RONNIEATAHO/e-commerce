<?php
session_start();

// Redirect to login page if not logged in as admin
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: adminlogin.php");
    exit();
}

// Include database connection
include 'db_connection.php'; // Connect to the database

// Log admin activity
function logAdminActivity($action) {
    $logMessage = date('Y-m-d H:i:s') . " - Admin ID: {$_SESSION['admin_id']} - Action: $action" . PHP_EOL;
    file_put_contents('admin_activity.log', $logMessage, FILE_APPEND);
}

// Log dashboard access
logAdminActivity('Accessed Dashboard');

// Start timer for performance tracking
$startTime = microtime(true);

// Fetch data for dashboard sections
try {
    $userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $productCount = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $orderCount = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    $salesTotal = $pdo->query("SELECT SUM(total_price) FROM orders")->fetchColumn();
} catch (PDOException $e) {
    // Log database errors
    $logMessage = date('Y-m-d H:i:s') . " - Database Error: " . $e->getMessage() . PHP_EOL;
    file_put_contents('error.log', $logMessage, FILE_APPEND);
    echo "An error occurred. Please try again later.";
    exit();
}

// End timer and log performance
$endTime = microtime(true);
$loadTime = $endTime - $startTime;
$logMessage = date('Y-m-d H:i:s') . " - Dashboard Load Time: $loadTime seconds" . PHP_EOL;
file_put_contents('performance.log', $logMessage, FILE_APPEND);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css"> <!-- Link to your CSS file -->
</head>
<body>

    <header>
        <h1>Admin Dashboard</h1>
        <nav>
            <a href="manage_users.php">Manage Users</a>
            <a href="manage_products.php">Manage Products</a>
            <a href="manage_orders.php">Manage Orders</a>
            <a href="view_reports.php">View Reports</a>
            <a href="admin_logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <!-- Dashboard summary cards -->
        <div class="dashboard-cards">
            <div class="card">
                <h2>Users</h2>
                <p>Total: <?php echo $userCount; ?></p>
            </div>
            <div class="card">
                <h2>Products</h2>
                <p>Total: <?php echo $productCount; ?></p>
            </div>
            <div class="card">
                <h2>Orders</h2>
                <p>Total: <?php echo $orderCount; ?></p>
            </div>
            <div class="card">
                <h2>Sales</h2>
                <p>Total: $<?php echo number_format($salesTotal, 2); ?></p>
            </div>
        </div>

        <!-- Recent orders section -->
        <section>
            <h2>Recent Orders</h2>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch recent orders
                    try {
                        $stmt = $pdo->query("SELECT orders.id, users.name, orders.order_date, orders.total_price, orders.status FROM orders JOIN users ON orders.user_id = users.id ORDER BY orders.order_date DESC LIMIT 5");
                        while ($order = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>{$order['id']}</td>";
                            echo "<td>{$order['name']}</td>";
                            echo "<td>{$order['order_date']}</td>";
                            echo "<td>\${$order['total_price']}</td>";
                            echo "<td>{$order['status']}</td>";
                            echo "<td><a href='view_order.php?id={$order['id']}'>View</a></td>";
                            echo "</tr>";
                        }
                    } catch (PDOException $e) {
                        // Log database errors
                        $logMessage = date('Y-m-d H:i:s') . " - Database Error: " . $e->getMessage() . PHP_EOL;
                        file_put_contents('error.log', $logMessage, FILE_APPEND);
                        echo "<tr><td colspan='6'>An error occurred while fetching orders.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <!-- Feedback section -->
        <section>
            <h2>Feedback</h2>
            <form action="submit_feedback.php" method="POST">
                <label for="feedback">Your Feedback:</label>
                <textarea id="feedback" name="feedback" rows="4" required></textarea>
                <button type="submit">Submit</button>
            </form>
        </section>

        <!-- Logs section -->
        <section>
            <h2>Logss</h2>
            <div class="logs">
                <h3>Activity Log</h3>
                <pre><?php echo file_get_contents('admin_activity.log'); ?></pre>

                <h3>Performance Log</h3>
                <pre><?php echo file_get_contents('performance.log'); ?></pre>

                <h3>Error Log</h3>
                <pre><?php echo file_get_contents('error.log'); ?></pre>
            </div>
        </section>
    </main>

</body>
</html>