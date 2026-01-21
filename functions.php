<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = mysqli_connect("localhost", "root", "", "peminjaman_mobil_radius");

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// ================== LOGIN ==================
function login($data) {
    global $conn;

    $email = mysqli_real_escape_string($conn, $data['email']);
    $password = $data['password'];

    $result = mysqli_query($conn,
        "SELECT * FROM users 
         WHERE email='$email'
         LIMIT 1"
    );

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['login'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = strtolower($user['role']);
            return true;
        }
    }
    return false;
}

// ================== REGISTER ==================
function register($data) {
    global $conn;

    $email    = mysqli_real_escape_string($conn, $data['email']);
    $role     = mysqli_real_escape_string($conn, $data['role']);
    $password = password_hash($data['password'], PASSWORD_DEFAULT);

    // cek email
    $cek = mysqli_query($conn,
        "SELECT id FROM users WHERE email='$email'"
    );

    if (mysqli_num_rows($cek) > 0) {
        return false;
    }

    // insert data
    $query = "INSERT INTO users (email, password, role)
              VALUES ('$email', '$password', '$role')";

    if (!mysqli_query($conn, $query)) {
        die("Register error: " . mysqli_error($conn));
    }

    return true;
}

?>
