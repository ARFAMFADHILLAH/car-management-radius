<?php
require '../functions.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
 <link rel="stylesheet" href="../css/style.css">
 <link rel="stylesheet" href="../css/manajemenuser.css">

<title>Data User</title>
</head>
<body>

<?php include "../layout/sidebar.php"; ?>

  <!-- CONTENT -->
  <div class="content">

    <main>

      <div class="head-title">
        <h1>Daftar User</h1>
        <button class="btn btn-primary">
          <i class="fa-solid fa-plus"></i> Tambah User
        </button>
      </div>

      <table class="table">
        <thead>
          <tr>
            <th>No</th>
            <th>Email</th>
            <th>Role</th>
            <th>Dibuat</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>

        <?php
        $no = 1;
        $users = mysqli_query($conn, "SELECT * FROM users WHERE role = 'pengguna' ORDER BY id DESC");
        while ($user = mysqli_fetch_assoc($users)) {
        ?>
          <tr>
  <td data-label="No"><?= $no++ ?></td>
            <td data-label="Email"><?= htmlspecialchars($user['email']) ?></td>
            <td data-label="Role"><?= htmlspecialchars($user['role']) ?></td>
            <td data-label="Dibuat"><?= date('d M Y', strtotime($user['created_at'])) ?></td>
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
    <?php include "../layout/footer.php"; ?>

  </div>

</div>

</body>

</html>
