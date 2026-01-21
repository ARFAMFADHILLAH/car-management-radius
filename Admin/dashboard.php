<?php
session_start();
require '../functions.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['email'];

date_default_timezone_set('Asia/Jakarta');

/* ==========================
   SAPAAN
========================== */
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

/* ==========================
   STATISTIK DASHBOARD
========================== */

// Mobil sedang dipakai
$q_used = mysqli_query($conn, "
    SELECT COUNT(DISTINCT car_id) AS total
    FROM car_requests
    WHERE status = 'approved'
");
$mobil_dipakai = mysqli_fetch_assoc($q_used)['total'];

// Total mobil
$q_mobil = mysqli_query($conn, "SELECT COUNT(*) AS total FROM cars");
$total_mobil = mysqli_fetch_assoc($q_mobil)['total'];
$available = $total_mobil - $mobil_dipakai;


// Pengajuan pending
$q_pending = mysqli_query($conn, "
    SELECT COUNT(*) AS total 
    FROM car_requests 
    WHERE status = 'pending'
");
$pending = mysqli_fetch_assoc($q_pending)['total'];

// Total user
$q_user = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role = 'pengguna'");
$total_user = mysqli_fetch_assoc($q_user)['total'];

// Pending terbaru
$q_latest = mysqli_query($conn, "
    SELECT cr.id, cr.user_name, c.car_name, cr.start_datetime
    FROM car_requests cr
    JOIN cars c ON cr.car_id = c.id
    WHERE cr.status = 'pending'
    ORDER BY cr.created_at DESC
    LIMIT 5
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dashboard_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<?php include "../layout/sidebar.php"; ?>

<div class="content">

    <!-- TOPBAR -->
    <header class="topbar">
        <h1>Dashboard Admin</h1>
        <div class="user">
            <span><?= $greeting ?>, <?= htmlspecialchars($username) ?></span>
            <i class="fa-solid fa-user-shield"></i>
        </div>
    </header>

    <!-- STAT CARD -->
    <section class="cards">

        <div class="card blue">
            <i class="fa-solid fa-car"></i>
            <div>
                <p>Total Mobil</p>
                <h2><?= $available ?></h2>
            </div>
        </div>

        <div class="card red">
            <i class="fa-solid fa-ban"></i>
            <div>
                <p>Sedang Dipakai</p>
                <h2><?= $mobil_dipakai ?></h2>
            </div>
        </div>

        <div class="card yellow">
            <i class="fa-solid fa-clock"></i>
            <div>
                <p>Pengajuan Pending</p>
                <h2><?= $pending ?></h2>
            </div>
        </div>

        <div class="card green">
            <i class="fa-solid fa-users"></i>
            <div>
                <p>Total User</p>
                <h2><?= $total_user ?></h2>
            </div>
        </div>

    </section>

    <!-- PENDING LIST -->
    <section class="box">
        <h3>Pengajuan Pending Terbaru</h3>

        <?php if (mysqli_num_rows($q_latest) == 0): ?>
            <p>Tidak ada pengajuan pending.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Mobil</th>
                        <th>Tanggal Mulai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($q_latest)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['user_name']) ?></td>
                            <td><?= htmlspecialchars($row['car_name']) ?></td>
                            <td><?= date('d M Y H:i', strtotime($row['start_datetime'])) ?></td>
                            <td>
                                <a href="approval_detail.php?id=<?= $row['id'] ?>" class="btn-detail">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>

</div>

</body>
</html>
