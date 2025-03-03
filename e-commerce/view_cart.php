<?php
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Handle removing items from the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_item'])) {
    $index = $_POST['index'];
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex the array
    }
    header('Location: view_cart.php');
    exit();
}

// Handle confirming the order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {
    // Save the order to the database (you can implement this part)
    // Clear the cart after confirming the order
    unset($_SESSION['cart']);
    header('Location: order_confirmation.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .cart-item {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .cart-item h2 {
            margin-top: 0;
            color: #555;
        }
        .cart-item p {
            margin: 5px 0;
            color: #777;
        }
        .cart-item form {
            display: inline;
        }
        .total {
            font-size: 1.5em;
            font-weight: bold;
            color: #333;
            text-align: right;
            margin-top: 20px;
        }
        .confirm-order {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            margin-top: 20px;
        }
        .confirm-order:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <h1>Your Cart</h1>
    <?php if (empty($_SESSION['cart'])): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <?php foreach ($_SESSION['cart'] as $index => $item): ?>
            <div class="cart-item">
                <h2><?php echo htmlspecialchars($item['name']); ?></h2>
                <p>Price: $<?php echo htmlspecialchars($item['price']); ?></p>
                <p>Quantity: <?php echo htmlspecialchars($item['quantity']); ?></p>
                <form method="POST" action="view_cart.php">
                    <input type="hidden" name="index" value="<?php echo $index; ?>">
                    <button type="submit" name="remove_item">Remove</button>
                </form>
            </div>
        <?php endforeach; ?>

        <!-- Calculate and display the total -->
        <?php
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        ?>
        <div class="total">Total: $<?php echo number_format($total, 2); ?></div>

        <!-- Confirm Order Button -->
        <form method="POST" action="view_cart.php">
            <button type="submit" name="confirm_order" class="confirm-order">Confirm Order</button>
        </form>
    <?php endif; ?>
</body>
</html>