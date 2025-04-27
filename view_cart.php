<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$cartItems = [];
$total = 0;
$error = "";

// Remove item from cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_item_id'])) {
    $removeId = $_POST['remove_item_id'];
    $stmt = $conn->prepare("DELETE FROM cart_items WHERE cart_item_id = :removeId");
    $stmt->bindParam(':removeId', $removeId, PDO::PARAM_INT);
    $stmt->execute();
    header('Location: view_cart.php');
    exit();
}

// Confirm order - UPDATED SECTION
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {
    $orderDate = date('Y-m-d H:i:s');
    $status = 'Pending';

    // Begin transaction
    $conn->beginTransaction();

    try {
        // Get cart ID
        $stmt = $conn->prepare("SELECT cart_id FROM cart WHERE user_id = :userId");
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $cartRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cartRow) {
            $cartId = $cartRow['cart_id'];

            // Get cart items and calculate total
            $itemsStmt = $conn->prepare("
                SELECT ci.product_id, ci.quantity, p.price
                FROM cart_items ci 
                JOIN products p ON ci.product_id = p.product_id 
                WHERE ci.cart_id = :cartId
            ");
            $itemsStmt->bindParam(':cartId', $cartId, PDO::PARAM_INT);
            $itemsStmt->execute();
            $cartItemsForOrder = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($cartItemsForOrder)) {
                throw new Exception("No items in cart to place an order.");
            }

            $totalPrice = 0;
            foreach ($cartItemsForOrder as $item) {
                $totalPrice += $item['quantity'] * $item['price'];
            }

            // Create order record
            $orderStmt = $conn->prepare("
                INSERT INTO orders (user_id, order_date, status, total_amount) 
                VALUES (:userId, :orderDate, :status, :totalAmount)
            ");
            $orderStmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $orderStmt->bindParam(':orderDate', $orderDate, PDO::PARAM_STR);
            $orderStmt->bindParam(':status', $status, PDO::PARAM_STR);
            $orderStmt->bindParam(':totalAmount', $totalPrice, PDO::PARAM_STR);
            $orderStmt->execute();
            
            // Get the new order ID
            $orderId = $conn->lastInsertId();

            // Insert order items
            foreach ($cartItemsForOrder as $item) {
                $orderItemStmt = $conn->prepare("
                    INSERT INTO order_items (order_id, product_id, quantity, price) 
                    VALUES (:orderId, :productId, :quantity, :price)
                ");
                $orderItemStmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
                $orderItemStmt->bindParam(':productId', $item['product_id'], PDO::PARAM_INT);
                $orderItemStmt->bindParam(':quantity', $item['quantity'], PDO::PARAM_INT);
                $orderItemStmt->bindParam(':price', $item['price'], PDO::PARAM_STR);
                $orderItemStmt->execute();
            }

            // Clear cart
            $deleteItemsStmt = $conn->prepare("DELETE FROM cart_items WHERE cart_id = :cartId");
            $deleteItemsStmt->bindParam(':cartId', $cartId, PDO::PARAM_INT);
            $deleteItemsStmt->execute();

            // Commit transaction
            $conn->commit();
            
            // After successful save, redirect
            header('Location: order_confirmation.php');
            exit();
        } else {
            throw new Exception("Cart not found for user.");
        }
    } catch (Exception $e) {
        // Rollback transaction if anything fails
        $conn->rollBack();
        $error = "Order failed: " . $e->getMessage();
    }
}

// Load cart items with images
$stmt = $conn->prepare("SELECT cart_id FROM cart WHERE user_id = :userId");
$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmt->execute();
$cartRow = $stmt->fetch(PDO::FETCH_ASSOC);

if ($cartRow) {
    $cartId = $cartRow['cart_id'];
    $itemsStmt = $conn->prepare("
        SELECT ci.cart_item_id, ci.quantity, p.product_name, p.price, p.image_url
        FROM cart_items ci
        JOIN products p ON ci.product_id = p.product_id
        WHERE ci.cart_id = :cartId
    ");
    $itemsStmt->bindParam(':cartId', $cartId, PDO::PARAM_INT);
    $itemsStmt->execute();
    
    while ($item = $itemsStmt->fetch(PDO::FETCH_ASSOC)) {
        $item['subtotal'] = $item['quantity'] * $item['price'];
        $total += $item['subtotal'];
        $cartItems[] = $item;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <style>
        /* (Your CSS styles stay exactly the same, no change) */
        body {
            font-family: Arial, sans-serif;
            background-image: url('background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            margin: 0;
            padding: 0;
        }

        nav {
            background-color: #343a40;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav h1 {
            color: white;
            margin: 0;
        }

        .back-btn {
            background-color: #ffc107;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            color: #212529;
            font-weight: bold;
        }

        .container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 20px;
        }

        .cart-item {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .cart-item img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 10px;
        }

        .item-info {
            flex: 1;
        }

        .item-info h2 {
            margin: 0 0 10px;
        }

        .item-info p {
            margin: 5px 0;
        }

        .remove-btn {
            background-color: #dc3545;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .remove-btn:hover {
            background-color: #c82333;
        }

        .total {
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            margin-top: 30px;
        }

        .confirm-btn {
            display: block;
            width: 100%;
            background-color: #28a745;
            color: white;
            font-size: 18px;
            padding: 14px;
            margin-top: 20px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }

        .confirm-btn:hover {
            background-color: #218838;
        }

        .empty-message {
            text-align: center;
            margin-top: 50px;
            color: #666;
        }

        .error-message {
            color: red;
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<nav>
    <h1>Your Cart</h1>
    <a href="beverages.php" class="back-btn">← Back to Beverages</a>
</nav>

<div class="container">
    <?php if ($error): ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (empty($cartItems)): ?>
        <p class="empty-message">Your cart is currently empty.</p>
    <?php else: ?>
        <div id="cart-items">
            <?php foreach ($cartItems as $item): ?>
                <div class="cart-item" data-subtotal="<?= $item['subtotal'] ?>">
                    <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>">
                    <div class="item-info">
                        <h2><?= htmlspecialchars($item['product_name']) ?></h2>
                        <p>Price: $<?= number_format($item['price'], 2) ?></p>
                        <p>Quantity: <?= $item['quantity'] ?></p>
                        <p class="subtotal">Subtotal: $<?= number_format($item['subtotal'], 2) ?></p>
                        <form method="POST" style="margin-top:10px;">
                            <input type="hidden" name="remove_item_id" value="<?= $item['cart_item_id'] ?>">
                            <button type="submit" class="remove-btn">Remove</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="total" id="total-display">Total: $<?= number_format($total, 2) ?></div>

        <form method="POST">
            <button type="submit" name="confirm_order" class="confirm-btn">Confirm Order</button>
        </form>
    <?php endif; ?>
</div>

<script>
    const updateTotal = () => {
        let total = 0;
        document.querySelectorAll('.cart-item').forEach(item => {
            const subtotal = parseFloat(item.dataset.subtotal);
            if (!isNaN(subtotal)) total += subtotal;
        });
        document.getElementById('total-display').textContent = 'Total: $' + total.toFixed(2);
    };

    document.addEventListener('DOMContentLoaded', updateTotal);
</script>

</body>
</html>
