<?php
require 'functions.php';

if (isset($_POST['register'])) {

    if (register($_POST)) {
        echo "<script>
                alert('Registrasi berhasil');
                window.location='login.php';
              </script>";
    } else {
        echo "<script>alert('Username atau email sudah terdaftar');</script>";
    }
}

?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Car Rental Radius - Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/register.css">
</head>
<body>

<div class="register-container">
    <div class="register-card">

        <div class="register-image">
            <h2>Car Rental Radius</h2>
            <p>Buat akun dan mulai perjalananmu</p>
        </div>

        <div class="register-form">
            <h1>Daftar Akun</h1>

            <form method="POST" action="">
    <div class="form-group">
        <input type="email" name="email" placeholder="Email Address" required>
    </div>

    <div class="form-group">
        <select name="role" required>
            <option value="pengguna" selected>Pengguna</option>
        </select>
    </div>

    <div class="form-row password-wrapper">
        <input type="password" id="password" name="password" placeholder="Password" required>
        <span class="toggle-password" onclick="togglePassword()">👁</span>
    </div>

    <button type="submit" name="register" class="btn-register">
        Buat Akun
    </button>
</form>


            <div class="links">
                <a href="login.php">Already have an account? Login</a>
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
