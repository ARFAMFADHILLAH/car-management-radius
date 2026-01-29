<?php
session_start();
require '../functions.php'; 



$users_id = $_SESSION['user_id'];

$query = "
    SELECT 
        cr.user_name,
        cr.division,
        cr.phone,
        cr.start_datetime,
        cr.end_datetime,
        cr.purpose,
        cr.status,
        cr.created_at,
        c.car_name
    FROM car_requests cr
    JOIN cars c ON cr.car_id = c.id
    WHERE cr.users_id = '$users_id'
    ORDER BY cr.created_at DESC
";


$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Pengajuan</title>
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon_io/favicon-32x32.png">
    <link rel="stylesheet" href="../css/riwayat_pengajuan.css">
<link rel="icon" href="../favicon_io/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/style.css">

</head>
<body>

<?php include "../layout/sidebaruser.php"; ?>

    <!-- CONTENT -->
    <div class="content">
        <h2>Riwayat Pengajuan Peminjaman</h2>

        <?php if (mysqli_num_rows($result) == 0): ?>
            <div class="empty">
                Belum ada pengajuan peminjaman.
            </div>
        <?php else: ?>
            </br>

            <a class="btn btn-success" href="ajukan_pengembalian.php">
                    Ajukan Pengembalian
                </a></br></br>

            <table>
                <thead>
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Divisi</th>
        <th>No HP</th>
        <th>Mobil</th>
        <th>Tanggal</th>
        <th>Keperluan</th>
        <th>Status</th>
        <th>Diajukan</th>
    </tr>
</thead>

                <tbody>
<?php $no = 1; ?>
<?php while ($row = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($row['user_name']) ?></td>
        <td><?= htmlspecialchars($row['division']) ?></td>
        <td><?= htmlspecialchars($row['phone']) ?></td>
        <td><?= htmlspecialchars($row['car_name']) ?></td>
        <td>
            <?= date('d M Y', strtotime($row['start_datetime'])) ?>
            -
            <?= date('d M Y', strtotime($row['end_datetime'])) ?>
        </td>
        <td><?= htmlspecialchars($row['purpose']) ?></td>
        <td>
            <span class="badge <?= $row['status'] ?>">
                <?= ucfirst($row['status']) ?>
            </span>
        </td>
        <td>
            <?= date('d M Y H:i', strtotime($row['created_at'])) ?>
        </td>
    </tr>
<?php endwhile; ?>
</tbody>

            </table>
        <?php endif; ?>
    </div>
<footer>
      &copy; 2026 Car Rental Radius
    </footer>
</div>
</body>
</html>
