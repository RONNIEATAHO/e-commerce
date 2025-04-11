<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle adding items to the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $item = [
        'name' => $_POST['name'],
        'price' => $_POST['price'],
        'quantity' => $_POST['quantity']
    ];
    array_push($_SESSION['cart'], $item);
    header('Location: view_cart.php'); // Redirect to the cart page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beverages</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            font-size: 3.5rem;
            text-align: center;
            font-family: 'Georgia', serif;
            color: #d35400;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* Product Section */
        .product-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .product-item {
            background-color: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .product-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .product-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
            transition: transform 0.2s ease-in-out;
        }

        .product-item img:hover {
            transform: scale(1.05);
        }

        .product-item h2 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .product-item p {
            font-size: 1.1rem;
            color: #7f8c8d;
            margin-bottom: 15px;
        }

        .product-item form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .product-item label {
            font-size: 1rem;
            margin-bottom: 5px;
            color: #34495e;
        }

        .product-item input[type="number"] {
            width: 60px;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #bdc3c7;
            border-radius: 5px;
            text-align: center;
            font-size: 1rem;
        }

        .product-item button {
            background-color: #e67e22;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }

        .product-item button:hover {
            background-color: #d35400;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            h1 {
                font-size: 2.5rem;
            }

            .product-section {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <main>
        <h1>BEVERAGES</h1>
        <section class="product-section">
            <!-- Example Beverage Item -->
            <div class="product-item">
                <img src="redwine.jpg" alt="WINE">
                <h2>WINE</h2>
                <p>Price: $10.00</p>
                <form method="POST" action="beverages.php">
                    <input type="hidden" name="name" value="WINE">
                    <input type="hidden" name="price" value="10.00">
                    <label for="quantity">Quantity:</label>
                    <input type="number" name="quantity" value="1" min="1" required>
                    <button type="submit" name="add_to_cart">Add to Cart</button>
                </form>
            </div>

            <!-- Add more beverage items here -->
            <div class="product-item">
                <img src="redwine.jpg" alt="RED WINE">
                <h2>RED WINE</h2>
                <p>Price: $15.00</p>
                <form method="POST" action="beverages.php">
                    <input type="hidden" name="name" value="RED WINE">
                    <input type="hidden" name="price" value="15.00">
                    <label for="quantity">Quantity:</label>
                    <input type="number" name="quantity" value="1" min="1" required>
                    <button type="submit" name="add_to_cart">Add to Cart</button>
                </form>
            </div>

            <div class="product-item">
                <img src="redwine.jpg" alt="LIQUOR">
                <h2>LIQUOR</h2>
                <p>Price: $25.00</p>
                <form method="POST" action="beverages.php">
                    <input type="hidden" name="name" value="LIQUOR">
                    <input type="hidden" name="price" value="25.00">
                    <label for="quantity">Quantity:</label>
                    <input type="number" name="quantity" value="1" min="1" required>
                    <button type="submit" name="add_to_cart">Add to Cart</button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>