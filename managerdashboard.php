<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header('Location: login.php');
    exit();
}

include 'db_connection.php';

// Fetch manager profile info
$manager_id = $_SESSION['user_id'];
$manager_query = "SELECT profile_photo, Username FROM login WHERE id = $manager_id";
$manager_result = $conn->query($manager_query);
$manager = $manager_result->fetch(PDO::FETCH_ASSOC);
$profile_image = $manager && $manager['profile_photo'] ? $manager['profile_photo'] : 'default-profile.jpg';
$username = $manager['Username'] ?? 'Manager';

// Fetch categories and products
$categories_result = $conn->query("SELECT * FROM categories");
$selected_category = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
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
        * { box-sizing: border-box; margin: 0; padding: 0; }

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
            width: 100%;
            background-color: #2c3e50;
            color: white;
            padding: 15px 20px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar .logo {
            font-size: 24px;
            font-weight: bold;
        }

        .navbar .search-form {
            display: flex;
            gap: 10px;
        }

        .navbar input, .navbar select {
            padding: 6px;
            border: none;
            border-radius: 4px;
        }

        .navbar button {
            background-color: #e67e22;
            color: white;
            border: none;
            padding: 6px 12px;
            cursor: pointer;
            border-radius: 4px;
        }

        .layout {
            display: flex;
            padding-top: 70px;
        }

        .sidebar {
            width: 220px;
            background-color: #34495e;
            color: white;
            padding: 20px;
            min-height: 100vh;
            position: fixed;
            top: 70px;
            left: 0;
        }

        .sidebar img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 10px;
            display: block;
        }

        .sidebar .username {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .sidebar a {
            display: block;
            padding: 10px;
            color: white;
            text-decoration: none;
            margin-bottom: 8px;
            background-color: #3d566e;
            border-radius: 4px;
        }

        .sidebar a:hover {
            background-color: #1abc9c;
        }

        .main-content {
            margin-left: 240px;
            padding: 20px;
            flex-grow: 1;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .menu-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .menu-item {
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            width: 220px;
            text-align: center;
        }

        .menu-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 6px;
            margin-bottom: 10px;
        }

        .menu-item h3 {
            margin-bottom: 8px;
        }

        .menu-item p {
            color: #777;
        }

        @media screen and (max-width: 768px) {
            .layout {
                flex-direction: column;
            }

            .sidebar {
                position: relative;
                width: 100%;
                top: 0;
                height: auto;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo">ORDER NOW</div>
    <form method="GET" class="search-form" id="filterForm">
        <select name="category_id" onchange="document.getElementById('filterForm').submit()">
            <option value="0" <?= $selected_category == 0 ? 'selected' : '' ?>>All Categories</option>
            <?php while ($cat = $categories_result->fetch(PDO::FETCH_ASSOC)): ?>
                <option value="<?= $cat['category_id'] ?>" <?= $selected_category == $cat['category_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['category_name']) ?>
                </option>
            <?php endwhile; ?>
        </select>
        <input type="text" name="search" placeholder="Search..." value="<?= htmlspecialchars($search_term) ?>">
        <button type="submit">Search</button>
    </form>
</div>

<div class="layout">
    <div class="sidebar">
        <img src="uploads/<?= htmlspecialchars($profile_image) ?>" alt="Profile Photo">
        <div class="username"><?= htmlspecialchars($username) ?></div>
        <a href="add_product.php">Add Product</a>
        <a href="view_orders.php">View Orders</a>
        <a href="view_served_orders.php">Orders Served</a>
        <a href="most_frequent_product.php">Top Products</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="main-content">
        <h1>Available Stock</h1>
        <div class="menu-container">
            <?php if ($products_result->rowCount() > 0): ?>
                <?php while ($product = $products_result->fetch(PDO::FETCH_ASSOC)): ?>
                    <?php
                        $image_path = htmlspecialchars($product['image_url']);
                        $final_path = (str_starts_with($image_path, 'http') || str_starts_with($image_path, 'uploads') || str_starts_with($image_path, 'images')) 
                            ? $image_path 
                            : "product_images/$image_path";
                    ?>
                    <div class="menu-item">
                        <?php if (!empty($product['image_url'])): ?>
                            <img src="<?= $final_path ?>" alt="<?= htmlspecialchars($product['product_name']) ?>">
                        <?php else: ?>
                            <img src="product_images/default.jpg" alt="No image available">
                        <?php endif; ?>
                        <h3><?= htmlspecialchars($product['product_name']) ?></h3>
                        <p>Price: $<?= htmlspecialchars($product['price']) ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No products found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
