<?php
session_start();
require '../functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// ambil data dari session
$users_id = $_SESSION['user_id'];
$username = $_SESSION['email'];

date_default_timezone_set('Asia/Jakarta');

// Sapaan
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

/* ===============================
   DATA KETERSEDIAAN MOBIL
================================ */

// Total mobil
$q_mobil = mysqli_query($conn, "SELECT COUNT(*) AS total FROM cars");
$total_mobil = mysqli_fetch_assoc($q_mobil)['total'];

// Mobil sedang dipakai
$q_used = mysqli_query($conn, "
    SELECT COUNT(DISTINCT car_id) AS total
    FROM car_requests
    WHERE status = 'approved'
");
$mobil_dipakai = mysqli_fetch_assoc($q_used)['total'];


// Mobil sedang diservice
$q_mobil_service = mysqli_query($conn, "SELECT COUNT(*) AS total FROM cars WHERE active = 0");
$mobil_diservice = mysqli_fetch_assoc($q_mobil_service)['total'];


// Mobil yang tersedia
$available = $total_mobil - $mobil_dipakai - $mobil_diservice;

/* ===============================
   RIWAYAT PENGAJUAN USER
================================ */

$q_status = mysqli_query($conn, "
    SELECT status, COUNT(*) AS total
    FROM car_requests
    WHERE users_id = '$users_id'
    GROUP BY status
");

$stat = [
    'pending' => 0,
    'approved' => 0,
    'rejected' => 0
];

while ($row = mysqli_fetch_assoc($q_status)) {
    $stat[$row['status']] = $row['total'];
}


// foto profil 

// Ambil data user
$user_email = $_SESSION['email']; // sesuai session login
$query_user = mysqli_query($conn, "SELECT photo FROM users WHERE email = '$user_email'");
$user_data = mysqli_fetch_assoc($query_user);

$foto_profile = $user_data['photo'] 
    ? "../uploads/profile/" . $user_data['photo'] 
    : "../uploads/profile-default.png";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Pengguna</title>
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon_io/favicon-32x32.png">
    <link rel="icon" href="../favicon_io/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dashboard_user.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<?php include "../layout/sidebaruser.php"; ?>

<div class="content">

    <header class="topbar">
    <h1>Dashboard Pengguna</h1>

    <div class="user">
        <img src="<?= $foto_profile ?>" alt="Foto Profil" class="topbar-profile">
        <span><?= $greeting ?>, <?= htmlspecialchars($username) ?></span>
    </div>
</header>



    <div class="info-grid">

        <!-- KETERSEDIAAN MOBIL -->
        <div class="info-box">
            <h4><i class="fa-solid fa-car"></i> Ketersediaan Mobil</h4>
            <ul>
                <li><i class="fa-solid fa-car-side"></i> Total Mobil : <b><?= $total_mobil ?></b></li>
                <li><i class="fa-solid fa-ban"></i> Sedang Dipakai : <b><?= $mobil_dipakai ?></b></li>
                <li><i class="fa-solid fa-check"></i> Mobil yang Tersedia : <b><?= $available ?></b></li>
                <li><i class="fa-solid fa-tools"></i> Mobil yang di service : <b><?= $mobil_diservice ?></b></li>
            </ul>
        </div>

        <!-- RIWAYAT PENGAJUAN -->
        <div class="info-box">
            <h4><i class="fa-solid fa-file-lines"></i> Pengajuan Anda</h4>
            <ul>
                <li><i class="fa-solid fa-clock"></i> Pending : <b><?= $stat['pending'] ?></b></li>
                <li><i class="fa-solid fa-circle-check"></i> Disetujui : <b><?= $stat['approved'] ?></b></li>
                <li><i class="fa-solid fa-circle-xmark"></i> Ditolak : <b><?= $stat['rejected'] ?></b></li>
            </ul>
        </div>

    </div>

<?php include "../layout/footer.php"; ?>
</div>


</body>
</html>
