<?php
session_start();
require '../functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* ======================
   PROSES UPLOAD FOTO
====================== */
if (isset($_POST['upload'])) {
    $file = $_FILES['photo'];
    $allowed = ['jpg','jpeg','png','svg','webp'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if ($file['error'] === 0) {
        if (in_array($ext, $allowed)) {
            if ($file['size'] <= 2 * 1024 * 1024) { // 2MB
                $newName = 'user_' . $user_id . '_' . time() . '.' . $ext;
                $path = "../uploads/profile/" . $newName;

                if (move_uploaded_file($file['tmp_name'], $path)) {
                    mysqli_query($conn, "UPDATE users SET photo='$newName' WHERE id='$user_id'");
                }
            }
        }
    }
}

/* ======================
   PROSES EDIT PROFIL
====================== */
if (isset($_POST['edit'])) {
    $new_email = mysqli_real_escape_string($conn, $_POST['email']);
    $new_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);

    // update data
    $update = mysqli_query($conn, "
        UPDATE users SET 
        email = '$new_email',
        no_telp = '$new_telp'
        WHERE id = '$user_id'
    ");

    if ($update) {
        // reload halaman agar data terbaru muncul
        header("Location: profil.php");
        exit;
    } else {
        $error_msg = "Gagal memperbarui data!";
    }
}


/* ======================
   AMBIL DATA USER
====================== */
$query = mysqli_query($conn, "SELECT email, no_telp, role, created_at, photo FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($query);

if (!$user) {
    echo "Data user tidak ditemukan.";
    exit;
}

$foto = $user['photo'] 
    ? "../uploads/profile/" . $user['photo'] 
    : "../uploads/profile-default.png";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Saya</title>
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon_io/favicon-32x32.png">
<link rel="icon" href="../favicon_io/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/profiladmin.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include "../layout/sidebar.php"; ?>

<div class="content">
    <div class="profile-card">

        <img src="<?= $foto ?>" class="profile-img">

        <!-- FORM UPLOAD -->
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="photo" required>
            <button type="submit" name="upload">Upload Foto</button>
        </form>

        <h1><?= htmlspecialchars($user['email']) ?></h1>
        <p class="job"><?= htmlspecialchars(ucfirst($user['role'])) ?></p>

        <div class="info">
    <form method="post">
        <p>
            <strong>Email :</strong>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </p>
        <p>
            <strong>No. Telp :</strong>
            <input type="text" name="no_telp" value="<?= htmlspecialchars($user['no_telp']) ?>">
        </p>
        <p>
            <strong>Role :</strong>
            <span><?= htmlspecialchars($user['role']) ?></span>
        </p>
        <p>
            <strong>Bergabung :</strong>
            <span><?= date("d M Y", strtotime($user['created_at'])) ?></span>
        </p>
        <button type="submit" name="edit">Simpan Perubahan</button>
    </form>
    <?php if(isset($error_msg)) echo '<p style="color:red;">'.$error_msg.'</p>'; ?>
</div>

    </div>
</div>

</body>
</html>
