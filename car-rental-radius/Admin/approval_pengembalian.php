<?php
session_start();
require '../functions.php';


$data = mysqli_query($conn, "
    SELECT cr.*, c.car_name, c.plate_number, u.email
    FROM car_requests cr
    JOIN cars c ON cr.car_id = c.id
    JOIN users u ON cr.user_name = u.email
    WHERE cr.status = 'returned'
");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Approval Pengembalian</title>
     <link rel="icon" type="image/png" sizes="32x32" href="../favicon_io/favicon-32x32.png">
<link rel="icon" href="../favicon_io/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/approval_pengembalian.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="wrapper">
<?php include '../layout/sidebar.php'; ?>

<div class="content">
<h2>Approval Pengembalian Mobil</h2>

<table border="1" width="100%" cellpadding="8">
    <tr>
        <th>User</th>
        <th>Mobil</th>
        <th>Plat</th>
        <th>Waktu Kembali</th>
        <th>Kondisi</th>
        <th>KM Akhir</th>
        <th>Catatan</th>
        <th>Aksi</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($data)) : ?>
<tr>
    <td><?= $row['email']; ?></td>
    <td><?= $row['car_name']; ?></td>
    <td><?= $row['plate_number']; ?></td>
    <td><?= $row['return_datetime']; ?></td>
    <td><?= $row['condition_car']; ?></td>
    <td><?= $row['km_end']; ?></td>
    <td><?= $row['return_notes']; ?></td>
    <td>
        <form action="approval_pengembalian_proses.php" method="POST">
            <input type="hidden" name="request_id" value="<?= $row['id']; ?>">
            <input type="hidden" name="car_id" value="<?= $row['car_id']; ?>">
            <button type="submit" class="btn-submit">
                Approve
            </button>
        </form>
    </td>
</tr>
<?php endwhile; ?>

</table>

<footer>
      &copy; 2026 Car Rental Radius
    </footer>
</div>

</div>
</body>

</html>
