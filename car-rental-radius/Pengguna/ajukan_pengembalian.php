<?php
session_start();
require '../functions.php';

$email = $_SESSION['email'];


$data = mysqli_query($conn, "
    SELECT cr.id, c.car_name, c.plate_number
    FROM car_requests cr
    JOIN cars c ON cr.car_id = c.id
    WHERE cr.user_name = '$email'
    AND cr.status = 'approved'
");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Ajukan Pengembalian</title>
     <link rel="icon" type="image/png" sizes="32x32" href="../favicon_io/favicon-32x32.png">
<link rel="icon" href="../favicon_io/favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/ajukan_pengembalian.css">
</head>
<body>

<div class="wrapper">
<?php include '../layout/sidebaruser.php'; ?>

<div class="content">
<h2>Ajukan Pengembalian Mobil</h2>

<form action="ajukan_pengembalian_proses.php" method="POST">

    <label>Mobil</label>
    <select name="request_id" required>
        <option value="">-- Pilih Mobil --</option>
        <?php while ($row = mysqli_fetch_assoc($data)) : ?>
            <option value="<?= $row['id']; ?>">
                <?= $row['car_name']; ?> (<?= $row['plate_number']; ?>)
            </option>
        <?php endwhile; ?>
    </select>

    <label>Tanggal & Jam Pengembalian</label>
    <input type="datetime-local" name="return_datetime" required>

    <label>Kondisi Mobil</label>
    <select name="condition_car" required>
        <option value="">-- Pilih --</option>
        <option value="Baik">Baik</option>
        <option value="Rusak Ringan">Rusak Ringan</option>
        <option value="Rusak Berat">Rusak Berat</option>
    </select>

    <label>KM Akhir</label>
    <input type="number" name="km_end" required>

    <label>Catatan Pengembalian</label>
    <textarea name="return_notes"></textarea>

    <button type="submit" class="btn-submit">
        Ajukan Pengembalian
    </button>
</form>

</div>

</div>
<footer>
      &copy; 2026 Car Rental Radius
    </footer>
</body>
</html>
