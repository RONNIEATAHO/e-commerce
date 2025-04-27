<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header('Location: login.php');
    exit();
}

include 'db_connection.php';

// Fetch manager info
$manager_id = $_SESSION['user_id'];
$manager_query = $conn->query("SELECT profile_photo, Username FROM login WHERE id = $manager_id");
$manager = $manager_query->fetch(PDO::FETCH_ASSOC);
$profile_image = $manager && $manager['profile_photo'] ? $manager['profile_photo'] : 'default-profile.jpg';
$username = $manager['Username'] ?? 'Manager';

// Fetch categories
$categories = $conn->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    // Image upload
    $image_path = '';
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        $image_path = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);
    }

    // Get category name for 'category' column
    $stmt = $conn->prepare("SELECT category_name FROM categories WHERE category_id = ?");
    $stmt->execute([$category_id]);
    $cat_name = $stmt->fetchColumn();

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO products (category_id, product_name, description, price, image_url, category)
                            VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$category_id, $product_name, $description, $price, $image_path, $cat_name])) {
        $message = "Product added successfully!";
    } else {
        $message = "Error adding product.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f4f4;
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

        form {
            background: white;
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        form label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        form input, form select, form textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        form button {
            margin-top: 20px;
            background-color: #2ecc71;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .message {
            text-align: center;
            margin-top: 10px;
            color: green;
            font-weight: bold;
        }

        @media screen and (max-width: 768px) {
            .layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                position: relative;
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
    </div>

    <div class="layout">
        <div class="sidebar">
            <img src="uploads/<?= htmlspecialchars($profile_image) ?>" alt="Profile Photo">
            <div class="username"><?= htmlspecialchars($username) ?></div>
            <a href="managerdashboard.php">Dashboard</a>
            <a href="add_product.php">Add Product</a>
            <a href="view_orders.php">View Orders</a>
            <a href="view_served_orders.php">Orders Served</a>
            <a href="most_frequent_product.php">Top Products</a>
            <a href="logout.php">Logout</a>
        </div>

        <div class="main-content">
            <h1>Add New Product</h1>
            <?php if (!empty($message)): ?>
                <p class="message"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data">
                <label for="product_name">Product Name</label>
                <input type="text" name="product_name" required>

                <label for="description">Description</label>
                <textarea name="description" rows="4"></textarea>

                <label for="price">Price</label>
                <input type="number" step="0.01" name="price" required>

                <label for="category_id">Category</label>
                <select name="category_id" required>
                    <option value="">-- Select Category --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="image">Product Image</label>
                <input type="file" name="image">

                <button type="submit">Add Product</button>
            </form>
        </div>
    </div>

</body>
</html>
