<?php
include 'db_connection.php';

for ($i = 0; $i < 500; $i++) {
    $user_id = rand(1, 10); // Assuming you have 10 sample users
    $status = ['Pending', 'Shipped', 'Delivered', 'Cancelled'][rand(0,3)];
    $total_amount = rand(10, 200); // Random total between $10 and $200

    $order_date = date('Y-m-d H:i:s', strtotime("-" . rand(0,30) . " days"));

    // Insert into orders
    $stmt = $conn->prepare("INSERT INTO orders (user_id, status, total_amount, order_date) VALUES (:user_id, :status, :total_amount, :order_date)");
    $stmt->execute([
        ':user_id' => $user_id,
        ':status' => $status,
        ':total_amount' => $total_amount,
        ':order_date' => $order_date
    ]);

    $order_id = $conn->lastInsertId();

    // Insert random order items
    for ($j = 0; $j < rand(1,5); $j++) {
        $product_id = rand(1, 20); // Assuming 20 products exist
        $quantity = rand(1, 5);
        $price = rand(5, 50);

        $stmt2 = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)");
        $stmt2->execute([
            ':order_id' => $order_id,
            ':product_id' => $product_id,
            ':quantity' => $quantity,
            ':price' => $price
        ]);
    }
}
echo "500 sample orders inserted!";
?>
