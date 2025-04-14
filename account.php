<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// ========== STRUCTURAL MODULARIZATION ==========

function getSanitizedSessionValue($key) {
    return isset($_SESSION[$key]) ? htmlspecialchars($_SESSION[$key]) : '';
}

function getProfilePicturePath() {
    $file = getSanitizedSessionValue('profile_picture');
    return "uploads/" . $file;
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
    <h1>Welcome, <?= getSanitizedSessionValue('username'); ?></h1>
    <img src="<?= getProfilePicturePath(); ?>" alt="Profile Picture" width="150" height="150">
    <p>Profile Picture: <?= getSanitizedSessionValue('profile_picture'); ?></p>
</body>
</html>
