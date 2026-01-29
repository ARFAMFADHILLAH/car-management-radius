<?php
session_start();
require '../functions.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: approval_list.php");
    exit;
}

$id = (int) $_GET['id'];

/* =========================
   AMBIL DETAIL PENGAJUAN
========================= */
$query = "
    SELECT 
        cr.*,
        c.car_name,
        c.plate_number
    FROM car_requests cr
    JOIN cars c ON cr.car_id = c.id
    WHERE cr.id = $id
";

$result = mysqli_query($conn, $query);
$request = mysqli_fetch_assoc($result);

if (!$request) {
    echo "Data tidak ditemukan";
    exit;
}

/* =========================
   CEK BENTROK JADWAL
========================= */
$bentrok_query = "
    SELECT 
        user_name,
        start_datetime,
        end_datetime
    FROM car_requests
    WHERE car_id = {$request['car_id']}
    AND id != {$request['id']}
    AND status IN ('approved', 'pending')
    AND (
        start_datetime < '{$request['end_datetime']}'
        AND end_datetime > '{$request['start_datetime']}'
    )
";

$bentrok_result = mysqli_query($conn, $bentrok_query);
$bentrok = mysqli_num_rows($bentrok_result) > 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon_io/favicon-32x32.png">
<link rel="icon" href="../favicon_io/favicon.ico" type="image/x-icon">

    <meta charset="UTF-8">
    <title>Detail Approval</title>

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/approvalpeminjaman.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<?php include "../layout/sidebar.php"; ?>

<section class="container">
    <h3>Detail Pengajuan Peminjaman</h3>

    <!-- ROW -->
    <div class="detail-row">

        <!-- DATA PEMINJAM -->
        <div class="card">
            <div class="card-title">
                <i class="fas fa-user"></i> Data Peminjam
            </div>
            <p><b>Nama:</b> <?= htmlspecialchars($request['user_name']) ?></p>
            <p><b>Divisi:</b> <?= htmlspecialchars($request['division']) ?></p>
            <p><b>No HP:</b> <?= htmlspecialchars($request['phone']) ?></p>
            <p><b>Keperluan:</b> <?= htmlspecialchars($request['purpose']) ?></p>
        </div>

        <!-- DATA MOBIL -->
        <div class="card">
            <div class="card-title">
                <i class="fas fa-car"></i> Data Mobil
            </div>
            <p><b>Mobil:</b> <?= htmlspecialchars($request['car_name']) ?></p>
            <p><b>Plat:</b> <?= htmlspecialchars($request['plate_number']) ?></p>
        </div>

        <!-- TANGGAL -->
        <div class="card">
            <div class="card-title">
                <i class="fas fa-calendar"></i> Jadwal & Status
            </div>
            <p>
                <b>Mulai:</b>
                <?= date('d M Y H:i', strtotime($request['start_datetime'])) ?>
            </p>
            <p>
                <b>Selesai:</b>
                <?= date('d M Y H:i', strtotime($request['end_datetime'])) ?>
            </p>
            <p>
                <b>Status:</b>
                <span class="badge <?= $request['status'] ?>">
                    <?= ucfirst($request['status']) ?>
                </span>
            </p>
        </div>

    </div>

    <!-- ALERT BENTROK -->
    <?php if ($bentrok): ?>
        <div class="alert alert-danger">
            <strong><i class="fas fa-triangle-exclamation"></i> MOBIL LAGI DI PAKE</strong>
            <ul>
                <?php while ($b = mysqli_fetch_assoc($bentrok_result)): ?>
                    <li>
                        <?= htmlspecialchars($b['user_name']) ?> :
                        <?= date('d M Y H:i', strtotime($b['start_datetime'])) ?>
                        –
                        <?= date('d M Y H:i', strtotime($b['end_datetime'])) ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- AKSI -->
    <div class="action-btn">
        <?php if ($request['status'] === 'pending'): ?>
            <a href="approval_action.php?action=approve&id=<?= $request['id'] ?>"
               class="btn btn-approve">
                <i class="fas fa-check"></i> Setujui
            </a>

            <a href="approval_action.php?action=reject&id=<?= $request['id'] ?>"
               class="btn btn-reject">
                <i class="fas fa-times"></i> Tolak
            </a>
        <?php endif; ?>
    </div>

    <?php include "../layout/footer.php"; ?>
</section>

</body>
</html>
