<?php
session_start();

logLogoutActivity();         // Custom function for traceability
clearSessionAndRedirect();   // Isolate actions into meaningful units

// ========== FUNCTIONS BELOW ==========

function logLogoutActivity() {
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $role = $_SESSION['role'] ?? 'unknown';
        $log = date('Y-m-d H:i:s') . " - User '$username' (Role: $role) logged out." . PHP_EOL;
        file_put_contents('logout_activity.log', $log, FILE_APPEND);
    }
}

function clearSessionAndRedirect() {
    session_unset();             // Unset all session variables
    session_destroy();           // Destroy session
    header("Location: admin_login.php"); // Redirect
    exit();
}
?>
