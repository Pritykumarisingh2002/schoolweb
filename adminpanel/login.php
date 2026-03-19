<?php
session_start();
include 'dbconnect.php';

$section = $_GET['section'] ?? 'login'; // default section

// Handle Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $login_id = $_POST['login_id'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE login_id = ?");
    $stmt->execute([$login_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $login_id;
        header("Location: index");
        exit;
    } else {
        $error = "Invalid Login ID or Password.";
    }
}

// Handle Signup
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    $login_id = trim($_POST['login_id']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE login_id = ?");
    $stmt->execute([$login_id]);

    if ($stmt->rowCount() > 0) {
        $error = "User already exists.";
        $section = 'signup';
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (login_id, password) VALUES (?, ?)");
        $stmt->execute([$login_id, $password]);
        $success = "Account created successfully. Please login.";
        $section = 'login';
    }
}

// Handle Password Reset
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset'])) {
    $login_id = $_POST['login_id'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE login_id = ?");
    $stmt->execute([$login_id]);

    if ($stmt->rowCount() > 0) {
        $update = $pdo->prepare("UPDATE users SET password = ? WHERE login_id = ?");
        $update->execute([$new_password, $login_id]);
        $success = "Password reset successfully. Please login.";
        $section = 'login';
    } else {
        $error = "User not found.";
        $section = 'reset';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Authentication</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      background-color: #f1f1f1;
      margin: 0;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .container {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    h1.welcome-text {
      text-align: center;
      color: #333;
      font-size: 26px;
      margin-bottom: 20px;
      font-weight: bold;
    }

    .auth-box {
      position: relative;
      width: 400px;
      padding: 30px 40px;
      border-radius: 10px;
      background-color: #fff;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      overflow: hidden;
    }

    .auth-box::before {
      content: "";
      background: url('../images/jps_logo.jpeg') center center no-repeat;
      background-size: 500px 300px;
      opacity: 0.4;
      position: absolute;
      top: 20px;
      left: 0;
      right: 0;
      bottom: 0;
      z-index: 0;
      margin: 0 auto;
    }

    .auth-box h2,
    .auth-box form,
    .auth-box .error,
    .auth-box .message,
    .auth-box .links {
      position: relative;
      z-index: 1;
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    input[type="text"],
    input[type="password"],
    input[type="submit"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border-radius: 5px;
      border: 2px solid black;
    }

    input[type="submit"] {
      background-color:#1e1e2f;
      color: white;
      font-weight: bold;
      border: none;
      cursor: pointer;
    }

    input[type="submit"]:hover {
      background-color: #1e1e2f;
    }

    .links {
      text-align: center;
      margin-top: 10px;
    }

    .links a {
      text-decoration: none;
      color: #007bff;
      margin: 0 10px;
    }

    .message {
      color: green;
      font-weight: bold;
      text-align: center;
    }

    .error {
      color: red;
      font-weight: bold;
      text-align: center;
    }
  </style>
</head>
<body>

<div class="container">
  <h1 class="welcome-text">Website Admin Panel</h1>

  <div class="auth-box">
    <?php if (!empty($error)): ?>
      <div class="error"><?= $error ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
      <div class="message"><?= $success ?></div>
    <?php endif; ?>

    <?php if ($section === 'login'): ?>
      <h2>Login</h2>
      <form method="POST">
        <input type="text" name="login_id" placeholder="Login ID" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" name="login" value="Login">
      </form>
      <!-- <div class="links">
        <b><a href="?section=signup">Create Account</a></b>
        <b><a href="?section=reset">Forgot Password?</a></b>
      </div> -->

    <?php elseif ($section === 'signup'): ?>
      <h2>Sign Up</h2>
      <form method="POST">
        <input type="text" name="login_id" placeholder="Choose Login ID" required>
        <input type="password" name="password" placeholder="Choose Password" required>
        <input type="submit" name="signup" value="Sign Up">
      </form>
      <div class="links">
        <b><a href="?section=login">Back to Login</a></b>
      </div>

    <?php elseif ($section === 'reset'): ?>
      <h2>Reset Password</h2>
      <form method="POST">
        <input type="text" name="login_id" placeholder="Your Login ID" required>
        <input type="password" name="new_password" placeholder="New Password" required>
        <input type="submit" name="reset" value="Reset Password">
      </form>
      <div class="links">
        <a href="?section=login">Back to Login</a>
      </div>
    <?php endif; ?>
  </div>
</div>

</body>
</html>
