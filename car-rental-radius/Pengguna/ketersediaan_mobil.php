<?php
session_start();
require '../functions.php';



// ambil semua mobil
$cars = mysqli_query($conn, "
    SELECT id, car_name, plate_number
    FROM cars
    ORDER BY car_name ASC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ketersediaan Mobil</title>
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon_io/favicon-32x32.png">
<link rel="icon" href="../favicon_io/favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/ketersediaan_mobil.css">
</head>
<body>

<?php include '../layout/sidebaruser.php'; ?>

<div class="content">
    <h2>Ketersediaan Mobil</h2>

    <?php while ($car = mysqli_fetch_assoc($cars)) : ?>

        <?php
        // cek jadwal approved untuk mobil ini
        $car_id = $car['id'];
        $jadwal = mysqli_query($conn, "
            SELECT start_datetime, end_datetime
            FROM car_requests
            WHERE car_id = '$car_id'
            AND status = 'approved'
            ORDER BY start_datetime ASC
        ");
        ?>

        <div class="car-card">
            <div class="car-header">
                <h3><?= htmlspecialchars($car['car_name']) ?></h3>
                <span class="plate"><?= htmlspecialchars($car['plate_number']) ?></span>
            </div>

            <?php if (mysqli_num_rows($jadwal) == 0): ?>
                <div class="available">Tersedia</div>
            <?php else: ?>
                <div class="unavailable">Sedang / Akan Dipakai</div>

                <table class="schedule">
                    <thead>
                        <tr>
                            <th>Mulai</th>
                            <th>Selesai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($jadwal)): ?>
                            <tr>
                                <td><?= date('d M Y H:i', strtotime($row['start_datetime'])) ?></td>
                                <td><?= date('d M Y H:i', strtotime($row['end_datetime'])) ?></td>
                            </tr>
                        <?php endwhile ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

    <?php endwhile; ?>

    <!-- FOOTER -->
    <?php include "../layout/footer.php"; ?>
</div>

</body>
</html>
