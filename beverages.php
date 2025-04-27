<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header('Location: login.php');
    exit();
}

include 'db_connection.php';

// Fetch categories
$categories_result = $conn->query("SELECT * FROM categories");

// Get filters
$selected_category = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build query
$product_query = "SELECT * FROM products WHERE 1";
if ($selected_category > 0) {
    $product_query .= " AND category_id = $selected_category";
}
if (!empty($search_term)) {
    $search_term_escaped = htmlspecialchars($search_term, ENT_QUOTES, 'UTF-8');
    $product_query .= " AND product_name LIKE '%$search_term_escaped%'";
}
$products_result = $conn->query($product_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hotel Order Menu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('background.jpg'); /* Set your background image path */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            margin: 0;
            padding: 0;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 10px 20px;
            border-bottom: 1px solid #ddd;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .navbar-left, .navbar-center, .navbar-right {
            display: flex;
            align-items: center;
        }
        .menu-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            margin-right: 15px;
            cursor: pointer;
        }
        .logo {
            font-size: 1.5rem;
            color: #000;
        }
        .star {
            color: orange;
            font-size: 1.5rem;
            margin-left: 3px;
        }
        .search-box {
            width: 300px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px 0 0 4px;
            outline: none;
        }
        .search-btn {
            background-color: orange;
            color: white;
            border: none;
            padding: 9px 15px;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
            font-weight: bold;
        }
        select {
            padding: 9px;
            border-radius: 4px;
            margin-right: 10px;
        }
        .navbar-right .nav-item {
            margin-left: 20px;
            font-size: 1rem;
            cursor: pointer;
        }
        @media (max-width: 768px) {
            .search-box {
                width: 180px;
            }
            .navbar {
                flex-wrap: wrap;
                gap: 10px;
            }
            .navbar-center {
                flex: 1 1 100%;
                justify-content: center;
            }
        }
        main {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 12px;
            margin: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .menu-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }
        .menu-item {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            width: 220px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            text-align: center;
        }
        .menu-item:hover {
            transform: scale(1.05);
        }
        .menu-item h3 {
            margin-top: 10px;
            color: #444;
        }
        .menu-item p {
            margin: 5px 0;
            color: #777;
        }
        .menu-item img {
            max-width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 6px;
        }
        .add-to-cart-btn {
            margin-top: 10px;
            background-color: orange;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<header class="navbar">
    <div class="navbar-left">
        <button class="menu-btn">&#9776;</button>
        <div class="logo">ORDER NOW<span class="star">★</span></div>
    </div>
    <form class="navbar-center" method="GET" id="filterForm">
        <select name="category_id" onchange="document.getElementById('filterForm').submit()">
            <option value="0" <?= $selected_category == 0 ? 'selected' : '' ?>>-- All Categories --</option>
            <?php 
            if ($categories_result && $categories_result->rowCount() > 0):
                while ($cat = $categories_result->fetch(PDO::FETCH_ASSOC)):
            ?>
                <option value="<?= $cat['category_id'] ?>" <?= $selected_category == $cat['category_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['category_name']) ?>
                </option>
            <?php endwhile; endif; ?>
        </select>
        <input type="text" name="search" class="search-box" placeholder="Search products..." value="<?= htmlspecialchars($search_term) ?>">
        <button class="search-btn" type="submit">Search</button>
    </form>
    <div class="navbar-right">
        <div class="nav-item">👤 Account</div>
        <div class="nav-item">❓ Help</div>
        <div class="nav-item" onclick="window.location.href='view_cart.php'">🛒 Cart</div>
    </div>
</header>
<main>
    <h1>Explore Our Menu</h1>
    <div class="menu-container">
        <?php 
        if ($products_result && $products_result->rowCount() > 0): 
            while ($product = $products_result->fetch(PDO::FETCH_ASSOC)): 
                $image_path = htmlspecialchars($product['image_url']);
                $final_path = (str_starts_with($image_path, 'http') || str_starts_with($image_path, 'uploads') || str_starts_with($image_path, 'images')) 
                            ? $image_path 
                            : "product_images/$image_path";
        ?>
                <div class="menu-item">
                    <img src="<?= $final_path ?>" alt="<?= htmlspecialchars($product['product_name']) ?>">
                    <h3><?= htmlspecialchars($product['product_name']) ?></h3>
                    <p>Price: $<?= htmlspecialchars($product['price']) ?></p>
                    <form action="add_to_cart.php" method="post">
                        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                        <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['product_name']) ?>">
                        <input type="hidden" name="price" value="<?= $product['price'] ?>">
                        <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                    </form>
                </div>
        <?php 
            endwhile; 
        else: 
        ?>
            <p>No products found<?= $search_term ? " matching '$search_term'" : '' ?>.</p>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
