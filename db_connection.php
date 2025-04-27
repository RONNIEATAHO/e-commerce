<?php
// Database configuration
$host = 'localhost';
$dbname = 'order_placement';
$username = 'root';
$password = '';

try {
    // Create a new PDO instance
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Set PDO attributes for error handling
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Performance tracking function
    function logPerformance($endpoint, $responseTime) {
        global $conn;
        try {
            $stmt = $conn->prepare(
                "INSERT INTO performance_logs 
                (endpoint, response_time_ms, timestamp) 
                VALUES (?, ?, NOW())"
            );
            $stmt->execute([$endpoint, $responseTime]);
        } catch (PDOException $e) {
            error_log("Performance logging failed: " . $e->getMessage());
        }
    }

    // Register shutdown function for performance tracking
    register_shutdown_function(function() {
        if (isset($_SERVER["REQUEST_TIME_FLOAT"])) {
            $pageLoadTime = (microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]) * 1000;
            logPerformance($_SERVER['REQUEST_URI'], $pageLoadTime);
        }
    });

} catch (PDOException $e) {
    // Handle connection errors
    die("Database connection failed: " . $e->getMessage());
}
?>
