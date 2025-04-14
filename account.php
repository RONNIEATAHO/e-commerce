<?php
session_start();
include 'db_connection.php';

// ========== SECURITY CHECK ==========
if (!isAdminLoggedIn()) {
    redirectToLogin();
}

// ========== LOGGING ==========
logAdminActivity('Accessed Admin Dashboard');

// ========== FETCH DASHBOARD STATS ==========
try {
    $userCount = getRowCount($conn, 'login');
    $articleCount = getRowCount($conn, 'articles');
    $categoryCount = getRowCount($conn, 'categories');
    $mediaCount = getRowCount($conn, 'media_uploads');
} catch (PDOException $e) {
    logError("Database Error: " . $e->getMessage());
    die("An error occurred. Please try again later.");
}

// ========== HELPER FUNCTIONS ==========
function isAdminLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['role'] === 'admin';
}

function redirectToLogin() {
    header("Location: login.php");
    exit();
}

function logAdminActivity($action) {
    $log = date('Y-m-d H:i:s') . " - Admin ID: {$_SESSION['admin_id']} - Action: $action\n";
    file_put_contents('logs/admin_activity.log', $log, FILE_APPEND);
}

function getRowCount($conn, $table) {
    $stmt = $conn->query("SELECT COUNT(*) FROM $table");
    return $stmt->fetchColumn();
}

function logError($msg) {
    file_put_contents('logs/error.log', date('Y-m-d H:i:s') . " - $msg\n", FILE_APPEND);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>
    <header>
        <h1>Welcome, Admin <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    </header>

    <main class="dashboard">
        <div class="stat-card">Total Users: <span><?php echo $userCount; ?></span></div>
        <div class="stat-card">Total Articles: <span><?php echo $articleCount; ?></span></div>
        <div class="stat-card">Categories: <span><?php echo $categoryCount; ?></span></div>
        <div class="stat-card">Media Uploads: <span><?php echo $mediaCount; ?></span></div>
    </main>

    <footer>
        <a href="logout.php" class="logout-btn">Logout</a>
    </footer>
</body>
</html>
