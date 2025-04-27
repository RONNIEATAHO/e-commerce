<?php
session_start();
require 'db_connection.php'; // this should connect $conn (PDO)

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    // User is not logged in as a customer
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['product_name'], $_POST['price'])) {
    $product_id = (int) $_POST['product_id'];
    $quantity = 1;

    try {
        // Step 1: Check if user already has a cart
        $stmt = $conn->prepare("SELECT cart_id FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cart) {
            // No cart exists, create one
            $stmt = $conn->prepare("INSERT INTO cart (user_id) VALUES (?)");
            $stmt->execute([$user_id]);
            $cart_id = $conn->lastInsertId();
        } else {
            $cart_id = $cart['cart_id'];
        }

        // Step 2: Check if the product is already in the cart
        $stmt = $conn->prepare("SELECT * FROM cart_items WHERE cart_id = ? AND product_id = ?");
        $stmt->execute([$cart_id, $product_id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($item) {
            // Update quantity if already exists
            $stmt = $conn->prepare("UPDATE cart_items SET quantity = quantity + 1 WHERE cart_item_id = ?");
            $stmt->execute([$item['cart_item_id']]);
        } else {
            // Insert new product into cart
            $stmt = $conn->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$cart_id, $product_id, $quantity]);
        }

        // Redirect back with success message
        header("Location: beverages.php?success=1");
        exit();

    } catch (PDOException $e) {
        echo "Error adding to cart: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>
