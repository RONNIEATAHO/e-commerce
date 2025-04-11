<?php
session_start();
include 'db_connection.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch admin data from the database
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        // Login successful: set session variable for admin
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $admin['username'];
        header("Location: admin_dashboard.php"); // Redirect to dashboard
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="admin.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="login-container">
        <form class="login-form" action="admin_login.php" method="POST">
            <h1>Admin Login</h1>
            <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>