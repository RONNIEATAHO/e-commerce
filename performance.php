// performance.php
function trackPerformance() {
    $pageLoadTime = (microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]) * 1000;
    logPerformance($_SERVER['REQUEST_URI'], $pageLoadTime);
}

register_shutdown_function('trackPerformance');