<?php
session_start();
include 'db_connection.php'; // Include database connection

// Debugging: Ensure database connection is working
if (!isset($conn)) {
    die("Database connection failed. Check db_connection.php.");
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role']; // Selected role

    // Fetch user from the database
    $sql = "SELECT * FROM login WHERE username = :username AND role = :role";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':role', $role);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify password and login
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = strtolower($user['role']); // Ensure case-insensitive comparison

        // Set additional session variables for admins
        if ($_SESSION['role'] === 'admin') {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $user['id'];
        }

        // Redirect based on role
        switch ($_SESSION['role']) {
            case 'customer':
                header('Location: beverages.php');
                break;
            case 'waiter':
                header('Location: deliver_orders.php');
                break;
            case 'admin':
                header('Location: admindashboard.php');
                break;
            default:
                die("Invalid role.");
        }
        exit();
    } else {
        $login_error = "Wrong credentials. Please try again.";
    }
}

// Handle signup form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
    $role = $_POST['role'];

    // Insert new user into the database
    $sql = "INSERT INTO login (username, password, role) VALUES (:username, :password, :role)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);

    if ($stmt->execute()) {
        $signup_success = "Signup successful! Please login.";
    } else {
        $signup_error = "Signup failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php if (isset($login_error)) echo "<p style='color: red;'>$login_error</p>"; ?>
    <form method="POST" action="login.php">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="role" required>
            <option value="" disabled selected>Select Role</option>
            <option value="customer">Customer</option>
            <option value="waiter">Waiter</option>
            <option value="admin">Admin</option>
        </select>
        <input type="submit" name="login" value="Login">
    </form>

    <h2>Sign Up</h2>
    <?php if (isset($signup_success)) echo "<p style='color: green;'>$signup_success</p>"; ?>
    <?php if (isset($signup_error)) echo "<p style='color: red;'>$signup_error</p>"; ?>
    <form method="POST" action="login.php">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="role" required>
            <option value="" disabled selected>Select Role</option>
            <option value="customer">Customer</option>
            <option value="waiter">Waiter</option>
            <option value="admin">Admin</option>
        </select>
        <input type="submit" name="signup" value="Sign Up">
    </form>
</body>
</html>
