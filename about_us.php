<?php
session_start(); // Start the session for user management, if needed

// You can include any necessary backend PHP files for database or user management here
// Example: include('db_connection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="aboutus.css">
    <title>About Us - Olive Shoppers</title>
</head>
<?php include('header.php');?>               
<body>
    <header>
  
    </header>

    <section class="mission">
        <h2>Our Mission</h2>
        <p>To provide quality products and exceptional services to all our customers, ensuring a seamless shopping experience online.</p>
    </section>

    <section class="values">
        <h2>Our Values</h2>
        <ul>
            <li><strong>Integrity:</strong> We believe in honest and transparent business practices.</li>
            <li><strong>Customer Centric:</strong> Our customers are at the heart of everything we do.</li>
            <li><strong>Innovation:</strong> Continuously improving and innovating to meet the needs of our shoppers.</li>
        </ul>
    </section>

</body>
<?php include('footer.php');?> 
</html>
