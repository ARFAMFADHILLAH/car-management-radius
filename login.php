<?php
session_start();
require 'functions.php';


if (isset($_POST['login'])) {
    if (login($_POST)) {

        if ($_SESSION['role'] === 'admin') {
            header("Location: ./Admin/dashboard.php");
            exit;
        } elseif ($_SESSION['role'] === 'pengguna') {
            header("Location: Pengguna/dashboard.php");
            exit;
        } else {
            session_destroy();
            header("Location: login.php?error=role");
            exit;
        }

    } else {
        header("Location: login.php?error=login");
        exit;
    }
}
?>



<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Car Rental Radius - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/login.css">
</head>
<body>

<div class="login-container">
    <div class="login-card">

        <div class="login-image">
            <h2>Car Rental Radius</h2>
            <p>Solusi sewa mobil terpercaya</p>
        </div>

        <div class="login-form">
            <h1>Login</h1>

            <form method="POST">
    <div class="form-group">
        <input type="email" name="email" placeholder="Email" required>
    </div>

    <div class="form-group">
        <input type="password" id="password" name="password" placeholder="Password" required>
    </div>

    <button type="submit" name="login" class="btn-login">Login</button>
</form>



            <div class="links">
                <a href="#">Forgot Password?</a>
                <a href="register.php">Belum punya akun? Daftar Akun</a>
            </div>
        </div>

    </div>
</div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById("password");
    passwordInput.type = passwordInput.type === "password" ? "text" : "password";
}
</script>
</body>
</html>
