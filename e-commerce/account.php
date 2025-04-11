<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include('header.php');?>               

<head>
    <meta charset="UTF-8">
    <title>Account</title>
    <link rel="stylesheet" href="account.css">
</head>
<body>
    <h1>Welcome, <?= htmlspecialchars($_SESSION['username']); ?></h1>
    <img src="uploads/<?= htmlspecialchars($_SESSION['profile_picture']); ?>" alt="Profile Picture" width="150" height="150">
    <p>Profile Picture: <?= htmlspecialchars($_SESSION['profile_picture']); ?></p>
</body>
</html>
