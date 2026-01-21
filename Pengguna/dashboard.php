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

$q_total = mysqli_query($conn, "SELECT COUNT(*) AS total FROM cars");
$total_mobil = mysqli_fetch_assoc($q_total)['total'];

$q_used = mysqli_query($conn, "
    SELECT COUNT(DISTINCT car_id) AS total
    FROM car_requests
    WHERE status = 'approved'
");
$used = mysqli_fetch_assoc($q_used)['total'];
$available = $total_mobil - $used;

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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Pengguna</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dashboard_user.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<?php include "../layout/sidebaruser.php"; ?>

<div class="content">

    <h2>Dashboard</h2>

    <div class="welcome-box">
        <h3><?= $greeting ?>, <?= htmlspecialchars($username) ?></h3>
        <p>Ringkasan sistem peminjaman mobil.</p>
    </div>

    <div class="info-grid">

        <!-- KETERSEDIAAN MOBIL -->
        <div class="info-box">
            <h4><i class="fa-solid fa-car"></i> Ketersediaan Mobil</h4>
            <ul>
                <li><i class="fa-solid fa-car-side"></i> Total Mobil : <b><?= $total_mobil ?></b></li>
                <li><i class="fa-solid fa-ban"></i> Sedang Dipakai : <b><?= $used ?></b></li>
                <li><i class="fa-solid fa-check"></i> Mobil yang Tersedia : <b><?= $available ?></b></li>
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

</div>

</body>
</html>
