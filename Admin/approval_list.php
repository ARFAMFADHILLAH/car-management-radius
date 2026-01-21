<?php
session_start();
require '../functions.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$query = "
    SELECT 
        cr.id,
        cr.user_name,
        c.car_name,
        cr.start_datetime,
        cr.end_datetime,
        cr.status,
        cr.car_id
    FROM car_requests cr
    JOIN cars c ON cr.car_id = c.id
    ORDER BY cr.created_at DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Approval Peminjaman</title>

<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/approvalpeminjaman.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<?php include "../layout/sidebar.php"; ?>

<div class="content">

<section class="container">
    <h3>Approval Peminjaman Mobil</h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Mobil</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Bentrok</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>

        <?php $no = 1; while ($row = mysqli_fetch_assoc($result)) : ?>

        <?php
        /* ===============================
           CEK BENTROK JADWAL
        =============================== */
        $cekBentrok = mysqli_query($conn, "
            SELECT id FROM car_requests
            WHERE car_id = '{$row['car_id']}'
            AND status = 'approved'
            AND (
                start_datetime <= '{$row['end_datetime']}'
                AND end_datetime >= '{$row['start_datetime']}'
            )
            AND id != '{$row['id']}'
        ");

        $bentrok = mysqli_num_rows($cekBentrok) > 0;
        ?>

        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['user_name']) ?></td>
            <td><?= htmlspecialchars($row['car_name']) ?></td>
            <td>
    <?= date('d M Y H:i', strtotime($row['start_datetime'])) ?>
    &nbsp;–&nbsp;
    <?= date('d M Y H:i', strtotime($row['end_datetime'])) ?>
</td>

            </td>
            <td>
                <span class="badge <?= $row['status'] ?>">
                    <?= ucfirst($row['status']) ?>
                </span>
            </td>
            <td>
                <?php if ($bentrok): ?>
                    <span class="bentrok">
                        <i class="fa-solid fa-triangle-exclamation"></i> Ya
                    </span>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
            <td class="text-center">
                <a class="btn btn-detail" href="approval_detail.php?id=<?= $row['id'] ?>">
                    Detail
                </a>
            </td>
        </tr>

        <?php endwhile; ?>

        </tbody>
    </table>
</section>

<?php include "../layout/footer.php"; ?>

</div>
</body>
</html>
