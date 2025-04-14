<?php
session_start();
include 'db_connection.php';

// ========== SECURITY ==========
if (!isAdminLoggedIn()) redirectToLogin();
logAdminActivity('Accessed Dashboard');

// ========== FETCH STATS ==========
try {
    $userCount = getRowCount($conn, 'login');
    $productCount = getRowCount($conn, 'products');
    $orderCount = getRowCount($conn, 'orders');
} catch (PDOException $e) {
    logError("Database Error: " . $e->getMessage());
    die("An error occurred. Please try again later.");
}

// ========== HANDLE COCOMO INPUT ==========
$effortEstimation = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kloc'])) {
    $kloc = floatval($_POST['kloc']);
    $mode = $_POST['mode']; // organic, semi-detached, embedded

    $coefficients = [
        'organic' => [2.4, 1.05],
        'semi' => [3.0, 1.12],
        'embedded' => [3.6, 1.20]
    ];

    if (isset($coefficients[$mode])) {
        [$a, $b] = $coefficients[$mode];
        $effort = $a * pow($kloc, $b);
        $effortEstimation = "Estimated Effort: " . round($effort, 2) . " Person-Months";
    } else {
        $effortEstimation = "Invalid mode selected.";
    }
}

// ========== FUNCTIONS ==========
function isAdminLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['role'] === 'admin';
}
function redirectToLogin() {
    header("Location: login.php");
    exit();
}
function logAdminActivity($action) {
    $log = date('Y-m-d H:i:s') . " - Admin ID: {$_SESSION['admin_id']} - Action: $action" . PHP_EOL;
    file_put_contents('admin_activity.log', $log, FILE_APPEND);
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
    <title>Admin Dashboard with COCOMO</title>
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
        .stat, .cocomo {
            font-size: 18px;
            font-weight: bold;
            background-color: white;
            padding: 10px;
            margin: 10px auto;
            width: 50%;
            border-radius: 5px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
        }
        form {
            margin: 20px auto;
            padding: 15px;
            background: #fff;
            width: 50%;
            border-radius: 5px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
        }
        input, select {
            padding: 10px;
            margin: 10px;
            font-size: 16px;
        }
        input[type="submit"] {
            background-color: #28a745;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }
        input[type="submit"]:hover {
            background-color: #218838;
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
        }
        a:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<h1>Welcome, Admin <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>

<div class="stat">Total Users: <?php echo $userCount; ?></div>
<div class="stat">Total Products: <?php echo $productCount; ?></div>
<div class="stat">Total Orders: <?php echo $orderCount; ?></div>

<form method="post">
    <h2>COCOMO Estimation</h2>
    <label>Enter Project Size (in KLOC):</label><br>
    <input type="number" name="kloc" step="0.1" required>
    <br>
    <label>Select Mode:</label>
    <select name="mode" required>
        <option value="organic">Organic</option>
        <option value="semi">Semi-Detached</option>
        <option value="embedded">Embedded</option>
    </select>
    <br>
    <input type="submit" value="Estimate Effort">
</form>

<?php if ($effortEstimation): ?>
    <div class="cocomo"><?php echo $effortEstimation; ?></div>
<?php endif; ?>

<a href="logout.php">Logout</a>

</body>
</html>
