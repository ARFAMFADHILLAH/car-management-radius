<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

$username = $_SESSION['email'];

$hour = date("H");
if ($hour >= 5 && $hour < 12) {
    $greeting = "Selamat pagi";
} elseif ($hour >= 12 && $hour < 15) {
    $greeting = "Selamat siang";
} elseif ($hour >= 15 && $hour < 18) {
    $greeting = "Selamat sore";
} else {
    $greeting = "Selamat malam";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700,800" rel="stylesheet">

  <!-- Font Awesome -->
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.1.0/css/all.min.css" />

</head>
<body>

<div id="wrapper">

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <i class="fas fa-car"></i>
        <span>Car Rental Radius</span>
    </div>

    <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="approval_list.php"><i class="fas fa-key"></i> Approval Peminjaman</a>
    <a href="approval_pengembalian.php"><i class="fas fa-car-tunnel"></i> Approval Pengembalian</a>
    <a href="datamobil.php"><i class="fas fa-car"></i> Data Mobil</a>
    <a href="#"><i class="fas fa-file-invoice"></i> Jadwal Mobil</a>
    <a href="#"><i class="fas fa-chart-bar"></i> Laporan</a>
    <a href="#"><i class="fa-solid fa-user-plus"></i> Manajemen Admin</a>
    <a href="manajemenuser.php"><i class="fas fa-users"></i> Manajemen User</a>
    <a href="profil.php"><i class="fas fa-user"></i> Profil</a>
    <a href="#"><i class="fas fa-cog"></i> Settings</a>
    <a href="../logout.php"><i class="fas fa-arrow-right-from-bracket"></i> Logout</a>
</aside>

</body>
</html>

