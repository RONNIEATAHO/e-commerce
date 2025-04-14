<?php
session_start();

// Restrict access to logged-in users
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Initialize cart if it doesn't exist
initializeCart();

// Process cart request if triggered
handleCartSubmission();

function initializeCart() {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}

function handleCartSubmission() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
        $item = [
            'name' => $_POST['name'],
            'price' => $_POST['price'],
            'quantity' => $_POST['quantity']
        ];
        array_push($_SESSION['cart'], $item);

        // Optional logging for structural visibility
        logCartAction("Added item: {$item['name']} x{$item['quantity']}");

        header('Location: view_cart.php'); // Redirect to the cart page
        exit();
    }
}

function logCartAction($message) {
    $username = $_SESSION['username'] ?? 'guest';
    $log = date('Y-m-d H:i:s') . " - User: $username - $message" . PHP_EOL;
    file_put_contents('cart_actions.log', $log, FILE_APPEND);
}
?>
