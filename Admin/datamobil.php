<?php
require '../functions.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
 <link rel="stylesheet" href="../css/style.css">
 <link rel="stylesheet" href="../css/datamobil.css">

<title>Data Mobil</title>
</head>
<body>

<?php include "../layout/sidebar.php"; ?>

  <!-- CONTENT -->
  <div class="content">

    <main>

      <div class="head-title">
        <h1>Daftar Mobil</h1>
        <button class="btn btn-primary">
          <i class="fa-solid fa-plus"></i> Tambah Mobil
        </button>
      </div>

      <table class="table">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Mobil</th>
            <th>Plat Nomor</th>
            <th>Status</th>
            <th>Dibuat</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>

        <?php
        $no = 1;
        $dt_mobil = mysqli_query($conn, "SELECT * FROM cars ORDER BY id DESC");
        while ($mobil = mysqli_fetch_assoc($dt_mobil)) {
        ?>
          <tr>
  <td data-label="No"><?= $no++ ?></td>
  <td data-label="Nama Mobil"><?= htmlspecialchars($mobil['car_name']) ?></td>
  <td data-label="Plat Nomor"><?= htmlspecialchars($mobil['plate_number']) ?></td>

  <td data-label="Status">
    <?php if ($mobil['active'] == 1): ?>
      <span class="badge badge-success">Tersedia</span>
    <?php else: ?>
      <span class="badge badge-danger">Tidak Tersedia</span>
    <?php endif; ?>
  </td>

  <td data-label="Dibuat">
    <?= date('d M Y', strtotime($mobil['created_at'])) ?>
  </td>

  <td>
  <div class="action-buttons">
    <button class="btn btn-warning btn-sm">
      <i class="fas fa-edit"></i>
    </button>
    <button class="btn btn-danger btn-sm">
      <i class="fas fa-trash"></i>
    </button>
  </div>
</td>

</tr>

        <?php } ?>

        </tbody>
      </table>

    </main>

    <!-- FOOTER -->
    <footer>
      &copy; 2026 Car Rental Radius
    </footer>

  </div>

</div>

</body>

</html>
