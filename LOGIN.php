<?php
 session_start();
 include 'db_connection.php';

 if (!isset($conn)) {
  die("Database connection failed.");
 }

 function handleLogin($conn) {
  $username = trim($_POST['username']);
  $password = $_POST['password'];

  $sql = "SELECT * FROM login WHERE username = :username";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':username', $username);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user && password_verify($password, $user['password'])) {
  initializeSession($user);
  redirectUser($user['role']);
  exit();
  } else {
  return "Wrong credentials. Please try again.";
  }
 }

 function initializeSession($user) {
  $_SESSION['user_id'] = $user['id'];
  $_SESSION['username'] = $user['username'];
  $_SESSION['role'] = strtolower($user['role']);
  if ($_SESSION['role'] === 'admin') {
  $_SESSION['admin_logged_in'] = true;
  $_SESSION['admin_id'] = $user['id'];
  }
 }

 function redirectUser($role) {
  switch (strtolower($role)) {
  case 'customer': header('Location: beverages.php'); break;
  case 'waiter': header('Location: deliver_orders.php'); break;
  case 'admin': header('Location: admindashboard.php'); break;
  case 'manager': header('Location: managerdashboard.php'); break;
  default: die("Invalid role.");
  }
 }

 function handleSignup($conn) {
  $username = trim($_POST['username']);
  $email = trim($_POST['email']);
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $role = $_POST['role'];

  // Handle profile photo upload
  $profile_photo = NULL;
  if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
  $uploadDir = 'uploads/';
  if (!is_dir($uploadDir)) mkdir($uploadDir);
  $filename = uniqid() . '_' . basename($_FILES['profile_photo']['name']);
  $targetPath = $uploadDir . $filename;

  if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $targetPath)) {
  // Construct the full URL
  $baseURL = 'http://localhost/e-commerce/'; // Your base URL
  $profile_photo = $baseURL . $targetPath;  // Store full URL
  }
  }

  $sql = "INSERT INTO login (username, email, profile_photo, password, role)  
  VALUES (:username, :email, :profile_photo, :password, :role)";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':profile_photo', $profile_photo);
  $stmt->bindParam(':password', $password);
  $stmt->bindParam(':role', $role);

  if ($stmt->execute()) {
  header("Location: login.php?signup=success");
  exit();
  } else {
  return "Signup failed. Please try again.";
  }
 }

 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['login'])) {
  $login_error = handleLogin($conn);
  }
  if (isset($_POST['signup'])) {
  $signup_feedback = handleSignup($conn);
  if (strpos($signup_feedback, 'failed') !== false) {
  $signup_error = $signup_feedback;
  }
  }
 }
 ?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
  <meta charset="UTF-8">
  <title>Login / Signup</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
  /* Your original styles remain unchanged */
  * {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
  }
  body {
  font-family: "Segoe UI", sans-serif;
  background-image: url('background.jpg');
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  overflow-x: hidden;
  }
  nav {
  background-color: rgba(0, 0, 0, 0.6);
  padding: 10px 20px;
  color: white;
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: space-between;
  position: fixed;
  width: 100%;
  top: 0;
  z-index: 10;
  }
  nav .logo {
  font-weight: bold;
  font-size: 22px;
  }
  nav a {
  color: white;
  text-decoration: none;
  font-weight: 500;
  margin-left: auto;
  }
  .form-container {
  margin-top: 100px;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 10px;
  width: 100%;
  }
  .card {
  background: rgba(255, 255, 255, 0.2);
  border-radius: 20px;
  padding: 30px;
  max-width: 350px;
  width: 100%;
  backdrop-filter: blur(10px);
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
  color: white;
  }
  .card input, .card select {
  margin: 10px 0;
  padding: 12px;
  width: 100%;
  border: none;
  border-radius: 5px;
  font-size: 16px;
  }
  .card input[type="submit"] {
  background: #f04e4e;
  color: white;
  font-weight: bold;
  cursor: pointer;
  border: none;
  transition: background 0.3s;
  }
  .card input[type="submit"]:hover {
  background: #d43c3c;
  }
  .form-footer {
  margin-top: 15px;
  font-size: 14px;
  text-align: center;
  }
  .form-footer a {
  color: #fff;
  text-decoration: underline;
  cursor: pointer;
  }
  .error { color: #ff9999; font-size: 14px; }
  .success { color: #99ffcc; font-size: 14px; }
  @media (max-width: 768px) {
  nav {
  flex-direction: column;
  align-items: flex-start;
  }
  nav a {
  margin-left: 0;
  margin-top: 5px;
  }
  .card {
  padding: 20px;
  max-width: 90%;
  }
  }
  @media (max-width: 480px) {
  .card input, .card select {
  font-size: 14px;
  padding: 10px;
  }
  .card input[type="submit"] {
  font-size: 14px;
  }
  nav {
  padding: 10px 15px;
  }
  .form-footer {
  font-size: 13px;
  }
  }
  </style>
 </head>
 <body>

 <nav>
  <div class="logo">🗝 Key</div>
  <a href="home.php">Home</a>
 </nav>

 <div class="form-container">
  <div class="card" id="loginForm">
  <h2>Login</h2>
  <?php if (isset($login_error)) echo "<p class='error'>$login_error</p>"; ?>
  <form method="POST">
  <input type="text" name="username" placeholder="Username" required>
  <input type="password" name="password" placeholder="Password" required>
  <input type="submit" name="login" value="Login">
  </form>
  <div class="form-footer">
  Don’t have an account? <a onclick="showForm('signup')">Create Account</a>
  </div>
  </div>

  <div class="card" id="signupForm" style="display: none;">
  <h2>Sign Up</h2>
  <?php if (isset($signup_error)) echo "<p class='error'>$signup_error</p>"; ?>
  <form method="POST" enctype="multipart/form-data">
  <input type="text" name="username" placeholder="Username" required>
  <input type="email" name="email" placeholder="Email" required>
  <input type="file" name="profile_photo" accept="image/*">
  <input type="password" name="password" placeholder="Password" required>
  <select name="role" required>
  <option value="" disabled selected>Select Role</option>
  <option value="customer">Customer</option>
  <option value="waiter">Waiter</option>
  <option value="manager">Manager</option>
  <option value="admin">Admin</option>
  </select>
  <input type="submit" name="signup" value="Sign Up">
  </form>
  <div class="form-footer">
  Already have an account? <a onclick="showForm('login')">Login</a>
  </div>
  </div>
 </div>

 <script>
  function showForm(type) {
  document.getElementById('loginForm').style.display = (type === 'login') ? 'block' : 'none';
  document.getElementById('signupForm').style.display = (type === 'signup') ? 'block' : 'none';
  }

  <?php if (isset($_GET['signup']) && $_GET['signup'] === 'success'): ?>
  showForm('login');
  alert("Signup successful! Please log in.");
  <?php endif; ?>
 </script>

 </body>
 </html>