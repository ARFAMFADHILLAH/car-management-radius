<?php
session_start();
require '../functions.php';



// Ambil data mobil
$dt_mobil = mysqli_query($conn, "SELECT id, car_name, plate_number FROM cars ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ajukan Peminjaman</title>
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon_io/favicon-32x32.png">
<link rel="icon" href="../favicon_io/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/ajukan_peminjaman.css">
</head>
<body>

<div class="wrapper">

    <!-- SIDEBAR -->
    <?php include '../layout/sidebaruser.php'; ?>

    <!-- CONTENT -->
    <div class="content">
        <h2>Ajukan Peminjaman Mobil</h2>

        <div class="form-box">
            <p class="info">
                Silakan isi form berikut. Pengajuan akan diproses oleh admin.
                <br>Status awal: <b>Pending</b>
            </p>

            <form action="ajukan_peminjaman_proses.php" method="POST">

                <label>Nama</label>
                <input type="text" value="<?= $_SESSION['email'] ?>" readonly>

                <label>Divisi</label>
                <select name="division" required>
                    <option value="">-- Pilih Divisi --</option>
                    <option value="Sales">Sales</option>
                    <option value="Technical support">Technical support</option>
                    <option value="Admin & Finance">Admin & Finance</option>
                    <option value="SCM">SCM</option>
                    <option value="HRGA">HRGA</option>
                </select>

                <label>No HP</label>
                <input type="text" name="phone" required>

                <label>Mobil</label>
                <select name="car_id" required>
                    <option value="">-- Pilih Mobil --</option>
                    <?php while ($mobil = mysqli_fetch_assoc($dt_mobil)) : ?>
                        <option value="<?= $mobil['id']; ?>">
                            <?= $mobil['car_name']; ?> (<?= $mobil['plate_number']; ?>)
                        </option>
                    <?php endwhile; ?>
                </select>

                <label>Tanggal & Jam Mulai</label>
                <input type="datetime-local" name="start_datetime" required>

                <label>Tanggal & Jam Selesai</label>
                <input type="datetime-local" name="end_datetime" required>

                <label>Keperluan</label>
                <input type="text" name="purpose" required>

                <label>Tujuan</label>
                <textarea name="destination"></textarea>

                <label>Nama Customer</label>
                <input type="text" name="customer_name">

                <label>Catatan</label>
                <textarea name="notes"></textarea>

                <button class="btn-submit" type="submit">Ajukan Peminjaman</button>
            </form>
        </div>
    </div>

    <footer>
      &copy; 2026 Car Rental Radius
    </footer>
</div>

</body>
</html>
