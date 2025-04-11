<?php
session_start();
include 'db_connection.php'; // Include the database connection file

// Debug: Check if $conn is set and working
if (!isset($conn)) {
    die("Database connection is not initialized. Check db_connection.php.");
}

// Log activity
function logActivity($action) {
    $logMessage = date('Y-m-d H:i:s') . " - Action: $action" . PHP_EOL;
    file_put_contents('activity.log', $logMessage, FILE_APPEND);
}

// Log errors
function logError($error) {
    $logMessage = date('Y-m-d H:i:s') . " - Error: $error" . PHP_EOL;
    file_put_contents('error.log', $logMessage, FILE_APPEND);
}

// Log performance
function logPerformance($message) {
    $logMessage = date('Y-m-d H:i:s') . " - Performance: $message" . PHP_EOL;
    file_put_contents('performance.log', $logMessage, FILE_APPEND);
}

// Start timer for performance tracking
$startTime = microtime(true);

try {
    // Test the connection
    $conn->query("SELECT 1");
} catch (PDOException $e) {
    logError("Database connection failed: " . $e->getMessage());
    die("Database connection failed. Please try again later.");
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role']; // Selected role

        // Fetch user from the database
        $sql = "SELECT * FROM login WHERE username = :username AND role = :role";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':role', $role);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify password
        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Log login activity
            logActivity("User {$user['username']} logged in as {$user['role']}");

            // Redirect based on role
            switch ($user['role']) {
                case 'customer':
                    header('Location: beverages.php');
                    break;
                case 'waiter':
                    header('Location: deliver_orders.php');
                    break;
                case 'admin':
                    header('Location: admin_dashboard.php');
                    break;
                default:
                    logError("Invalid role for user {$user['username']}");
                    die("Invalid role.");
            }
            exit();
        } else {
            $login_error = "Wrong credentials. Please try again.";
            logError("Failed login attempt for username: $username");
        }
    }

    // Handle signup form submission
    if (isset($_POST['signup'])) {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
        $role = $_POST['role']; // Selected role

        // Insert new user into the database
        $sql = "INSERT INTO login (username, password, role) VALUES (:username, :password, :role)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $role);

        if ($stmt->execute()) {
            $signup_success = "Signup successful! Please login.";
            logActivity("New user signed up: $username with role: $role");
        } else {
            $signup_error = "Signup failed. Please try again.";
            logError("Signup failed for username: $username");
        }
    }

    // Handle feedback submission
    if (isset($_POST['feedback'])) {
        $feedback = $_POST['feedback'];
        $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';

        // Log feedback
        $logMessage = date('Y-m-d H:i:s') . " - Feedback from $username: $feedback" . PHP_EOL;
        file_put_contents('feedback.log', $logMessage, FILE_APPEND);
        $feedback_success = "Thank you for your feedback!";
    }
}

// End timer and log performance
$endTime = microtime(true);
$loadTime = $endTime - $startTime;
logPerformance("Page loaded in $loadTime seconds");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container, .signup-container, .feedback-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        input[type="text"], input[type="password"], select, textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
        .success {
            color: green;
            margin-bottom: 15px;
        }
        .toggle-form {
            color: #007bff;
            cursor: pointer;
            margin-top: 10px;
        }
        .toggle-form:hover {
            text-decoration: underline;
        }
        .forgot-password {
            display: block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }
        .forgot-password:hover {
            text-decoration: underline;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <!-- Login Form -->
    <div class="login-container" id="login-container">
        <h2>Login</h2>
        <?php if (isset($login_error)): ?>
            <p class="error"><?php echo $login_error; ?></p>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role" required>
                <option value="" disabled selected>Select Role</option>
                <option value="customer">Customer</option>
                <option value="waiter">Waiter/Waitress</option>
                <option value="admin">Admin</option>
            </select>
            <input type="submit" name="login" value="Login">
            <a href="contact_us.php" class="forgot-password">Forgot Password?</a>
            <p class="toggle-form" onclick="showSignup()">Don't have an account? Sign Up</p>
            <p class="toggle-form" onclick="showFeedback()">Give Feedback</p>
        </form>
    </div>

    <!-- Signup Form -->
    <div class="signup-container hidden" id="signup-container">
        <h2>Sign Up</h2>
        <?php if (isset($signup_success)): ?>
            <p class="success"><?php echo $signup_success; ?></p>
        <?php endif; ?>
        <?php if (isset($signup_error)): ?>
            <p class="error"><?php echo $signup_error; ?></p>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role" required>
                <option value="" disabled selected>Select Role</option>
                <option value="customer">Customer</option>
                <option value="waiter">Waiter/Waitress</option>
                <option value="admin">Admin</option>
            </select>
            <input type="submit" name="signup" value="Sign Up">
            <p class="toggle-form" onclick="showLogin()">Already have an account? Login</p>
        </form>
    </div>

    <!-- Feedback Form -->
    <div class="feedback-container hidden" id="feedback-container">
        <h2>Feedback</h2>
        <?php if (isset($feedback_success)): ?>
            <p class="success"><?php echo $feedback_success; ?></p>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <textarea name="feedback" placeholder="Your feedback..." required></textarea>
            <input type="submit" name="submit_feedback" value="Submit Feedback">
            <p class="toggle-form" onclick="showLogin()">Back to Login</p>
        </form>
    </div>

    <script>
        // Function to show the signup form and hide the login form
        function showSignup() {
            document.getElementById('login-container').classList.add('hidden');
            document.getElementById('signup-container').classList.remove('hidden');
            document.getElementById('feedback-container').classList.add('hidden');
        }

        // Function to show the login form and hide the signup form
        function showLogin() {
            document.getElementById('signup-container').classList.add('hidden');
            document.getElementById('login-container').classList.remove('hidden');
            document.getElementById('feedback-container').classList.add('hidden');
        }

        // Function to show the feedback form and hide the login/signup forms
        function showFeedback() {
            document.getElementById('login-container').classList.add('hidden');
            document.getElementById('signup-container').classList.add('hidden');
            document.getElementById('feedback-container').classList.remove('hidden');
        }
    </script>
</body>
</html>