<?php
require '../functions.php';

if(isset($_POST['action'])) {
    // Tambah Mobil
    if($_POST['action'] == 'add') {
        $name = mysqli_real_escape_string($conn, $_POST['car_name']);
        $plate = mysqli_real_escape_string($conn, $_POST['plate_number']);
        $active = isset($_POST['active']) ? 1 : 0;
        $noactive = isset($_POST['noactive']) ? 1 : 0;
        $Keterangan = mysqli_real_escape_string($conn, $_POST['Keterangan']);

        $gambar = null;
    if (!empty($_FILES['gambar']['name'])) {
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $gambar = time() . '_' . rand(100,999) . '.' . $ext;
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../uploads/mobil/" . $gambar);
    }

        mysqli_query($conn, "INSERT INTO cars (gambar, car_name, plate_number, active, noactive, Keterangan, created_at) VALUES ('$gambar', '$name', '$plate', '$active', '$noactive', '$Keterangan', NOW())");
        header("Location: datamobil.php");
        exit;
    }

    // Edit Mobil
    if($_POST['action'] == 'edit') {
        $id = $_POST['id'];
        // ambil gambar lama
    $old = mysqli_fetch_assoc(
        mysqli_query($conn, "SELECT gambar FROM cars WHERE id='$id'")
    );

    $gambar = $old['gambar'];

    if (!empty($_FILES['gambar']['name'])) {
        if ($gambar && file_exists("../uploads/mobil/".$gambar)) {
            unlink("../uploads/mobil/".$gambar);
        }

        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $gambar = time() . '_' . rand(100,999) . '.' . $ext;
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../uploads/mobil/" . $gambar);
    }
        $name = mysqli_real_escape_string($conn, $_POST['car_name']);
        $plate = mysqli_real_escape_string($conn, $_POST['plate_number']);
        $active = isset($_POST['active']) ? 1 : 0;
        $noactive = isset($_POST['noactive']) ? 1 : 0;
        $Keterangan = mysqli_real_escape_string($conn, $_POST['Keterangan']);

        mysqli_query($conn, "UPDATE cars SET gambar='$gambar', car_name='$name', plate_number='$plate', active='$active', Keterangan='$Keterangan' WHERE id='$id'");
        header("Location: datamobil.php");
        exit;
    }

    // Hapus Mobil
    if($_POST['action'] == 'delete') {
        $id = $_POST['id'];
        mysqli_query($conn, "DELETE FROM cars WHERE id='$id'");
        header("Location: datamobil.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/png" sizes="32x32" href="../favicon_io/favicon-32x32.png">
<link rel="icon" href="../favicon_io/favicon.ico" type="image/x-icon">

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
        <button class="btn btn-primary" onclick="openAddModal()">
  <i class="fa-solid fa-plus"></i> Tambah Mobil
</button>

      </div>

      <table class="table">
        <thead>
          <tr>
            <th>No</th>
            <th>Foto</th>
            <th>Nama Mobil</th>
            <th>Plat Nomor</th>
            <th>Status</th>
            <th>Keterangan</th>
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
  <td data-label="Foto">
  <?php if($mobil['gambar']) : ?>
    <img src="../uploads/mobil/<?= $mobil['gambar'] ?>" 
         style="width:130px; height:100px;">
  <?php else : ?>
    <span>-</span>
  <?php endif; ?>
</td>

  <td data-label="Nama Mobil"><?= htmlspecialchars($mobil['car_name']) ?></td>
  <td data-label="Plat Nomor"><?= htmlspecialchars($mobil['plate_number']) ?></td>

  <td data-label="Status">
    <?php if ($mobil['active'] == 1): ?>
      <span class="badge badge-success">Tersedia</span>
    <?php else: ?>
      <span class="badge badge-danger">Tidak Tersedia</span>
    <?php endif; ?>
  </td>

  <td data-label="Keterangan"><?= htmlspecialchars($mobil['Keterangan']) ?></td>

  <td data-label="Dibuat">
    <?= date('d M Y', strtotime($mobil['created_at'])) ?>
  </td>

  <td>
  <div class="action-buttons">
    <button class="btn btn-warning btn-sm" onclick="openEditModal(
  '<?= $mobil['id'] ?>',
  '<?= htmlspecialchars($mobil['car_name']) ?>',
  '<?= htmlspecialchars($mobil['plate_number']) ?>',
  '<?= $mobil['active'] ?>',
  '<?= $mobil['noactive'] ?>',
  '<?= $mobil['Keterangan'] ?>',
  '<?= $mobil['gambar'] ?>'
)">

  <i class="fas fa-edit"></i>
</button>
    <button class="btn btn-danger btn-sm" onclick="deleteCar(<?= $mobil['id'] ?>)">
  <i class="fas fa-trash"></i>
</button>

  </div>
</td>

</tr>

        <?php } ?>

        </tbody>
      </table>

<!-- MODAL ADD/EDIT -->
<div class="modal" id="carModal">
  <div class="modal-content">
    <span class="close-btn" onclick="closeModal()">&times;</span>

    <h2 id="modalTitle">Tambah Mobil</h2>

    <form method="POST" id="carForm" enctype="multipart/form-data">
      <input type="hidden" name="action" id="formAction" value="add">
      <input type="hidden" name="id" id="carId">

      <label>Foto Mobil</label>
<input type="file" name="gambar" accept="image/*">

<!-- preview gambar lama (edit) -->
<img id="previewImage" src="" style="max-width:120px; display:none; margin-top:10px;">


      <label>Nama Mobil</label>
      <input type="text" name="car_name" id="carName" required>

      <label>Plat Nomor</label>
      <input type="text" name="plate_number" id="plateNumber" required>

      <label>
        <input type="checkbox" name="active" id="carActive"> Tersedia
      </label>

      <label>
        <input type="checkbox" name="noactive" id="carnoActive"> Tidak Tersedia
      </label>

      <label>Keterangan</label>
        <input type="text" name="Keterangan" id="Keterangan" required>
      

      <button type="submit" class="btn btn-primary" id="modalBtn">Simpan</button>
    </form>
  </div>
</div>


    </main>

    <!-- FOOTER -->
    <footer>
      &copy; 2026 Car Rental Radius
    </footer>

  </div>

</div>


</body>
<script>
function openAddModal(){
  document.getElementById('modalTitle').innerText = "Tambah Mobil";
  document.getElementById('formAction').value = "add";
  document.getElementById('carId').value = "";
  document.getElementById('carName').value = "";
  document.getElementById('plateNumber').value = "";
  document.getElementById('carActive').checked = false;
  document.getElementById('carnoActive').checked = false;
  document.getElementById('Keterangan').value = "";
  document.getElementById('carModal').style.display = "block";
}

function openEditModal(id, name, plate, active, noactive, ket, gambar){
  document.getElementById('modalTitle').innerText = "Edit Mobil";
  document.getElementById('formAction').value = "edit";
  document.getElementById('carId').value = id;
  document.getElementById('carName').value = name;
  document.getElementById('plateNumber').value = plate;
  document.getElementById('carActive').checked = (active == 1);
  document.getElementById('carnoActive').checked = (active == 0);
  document.getElementById('Keterangan').value = ket;

  if(gambar){
    document.getElementById('previewImage').src = "../uploads/mobil/" + gambar;
    document.getElementById('previewImage').style.display = "block";
  } else {
    document.getElementById('previewImage').style.display = "none";
  }

  document.getElementById('carModal').style.display = "block";
}


function closeModal(){
  document.getElementById('carModal').style.display = "none";
}

window.onclick = function(event){
  if(event.target == document.getElementById('carModal')){
    closeModal();
  }
}

function deleteCar(id){
  if(confirm("Yakin ingin menghapus data mobil ini?")){
    const form = document.createElement('form');
    form.method = "POST";
    form.innerHTML = `
      <input type="hidden" name="action" value="delete">
      <input type="hidden" name="id" value="${id}">
    `;
    document.body.appendChild(form);
    form.submit();
  }
}
</script>


</html>
