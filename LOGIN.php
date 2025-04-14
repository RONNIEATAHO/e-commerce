<?php
session_start();
include 'db_connection.php'; // Include database connection

// Debugging: Ensure database connection is working
if (!isset($conn)) {
    die("Database connection failed. Check db_connection.php.");
}

// ========= NEW FUNCTIONAL MODULES =========

function handleLogin($conn) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Fetch user from DB
    $sql = "SELECT * FROM login WHERE username = :username AND role = :role";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':role', $role);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify password
    if ($user && password_verify($password, $user['password'])) {
        initializeSession($user);
        redirectUser($user['role']);
        exit();
    } else {
        return "Wrong credentials. Please try again.";
    }
    return null;
}

function initializeSession($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = strtolower($user['role']);

    if ($_SESSION['role'] === 'admin') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $user['id'];
    }
}

function redirectUser($role) {
    switch (strtolower($role)) {
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
}

function handleSignup($conn) {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $sql = "INSERT INTO login (username, password, role) VALUES (:username, :password, :role)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);

    if ($stmt->execute()) {
        return "Signup successful! Please login.";
    } else {
        return "Signup failed. Please try again.";
    }
}

// ========= ORIGINAL LOGIC WITH FUNCTION CALLS =========

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $login_error = handleLogin($conn); // Preserve original behavior
    }

    if (isset($_POST['signup'])) {
        $signup_feedback = handleSignup($conn);
        if (strpos($signup_feedback, 'successful') !== false) {
            $signup_success = $signup_feedback;
        } else {
            $signup_error = $signup_feedback;
        }
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
