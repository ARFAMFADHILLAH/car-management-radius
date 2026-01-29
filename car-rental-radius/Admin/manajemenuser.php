<?php
require '../functions.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

/* ======================
   PROSES TAMBAH / EDIT / HAPUS
====================== */
if (isset($_POST['action'])) {

    // TAMBAH USER
    if ($_POST['action'] == 'add') {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = mysqli_real_escape_string($conn, $_POST['role']);

        mysqli_query($conn, "INSERT INTO users (email, password, role, created_at)
                             VALUES ('$email', '$password', '$role', NOW())");

        header("Location: manajemenuser.php");
        exit;
    }

    // EDIT USER
    if ($_POST['action'] == 'edit') {
        $id = $_POST['id'];
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $role = mysqli_real_escape_string($conn, $_POST['role']);

        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            mysqli_query($conn, "UPDATE users SET email='$email', password='$password', role='$role' WHERE id='$id'");
        } else {
            mysqli_query($conn, "UPDATE users SET email='$email', role='$role' WHERE id='$id'");
        }

        header("Location: manajemenuser.php");
        exit;
    }

    // HAPUS USER
    if ($_POST['action'] == 'delete') {
        $id = $_POST['id'];
        mysqli_query($conn, "DELETE FROM users WHERE id='$id'");
        header("Location: manajemenuser.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/manajemenuser.css">
<link rel="icon" href="../favicon_io/favicon.ico">

<title>Manajemen User</title>
</head>
<body>

<?php include "../layout/sidebar.php"; ?>

<div class="content">
<main>

<div class="head-title">
    <h1>Daftar User</h1>
    <button class="btn btn-primary" onclick="openAddModal()">
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
$users = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
while ($user = mysqli_fetch_assoc($users)) {
?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= htmlspecialchars($user['email']) ?></td>
    <td><?= htmlspecialchars($user['role']) ?></td>
    <td><?= date('d M Y', strtotime($user['created_at'])) ?></td>
    <td>
        <div class="action-buttons">
            <button class="btn btn-warning btn-sm btn-edit"
    data-id="<?= $user['id'] ?>"
    data-email="<?= htmlspecialchars($user['email'], ENT_QUOTES) ?>"
    data-role="<?= $user['role'] ?>">
    <i class="fas fa-edit"></i>
</button>

            <button class="btn btn-danger btn-sm"
                onclick="deleteUser(<?= $user['id'] ?>)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </td>
</tr>
<?php } ?>

</tbody>
</table>


</main>
<!-- MODAL USER -->
<div class="custom-modal" id="userModal">
<div class="modal-content">
<span class="close-btn" onclick="closeModal()">&times;</span>

<h2 id="modalTitle">Tambah User</h2>

<form method="POST">
<input type="hidden" name="action" id="formAction" value="add">
<input type="hidden" name="id" id="userId">

<label>Email</label>
<input type="email" name="email" id="userEmail" required>

<label id="passwordLabel">Password</label>
<input type="password" name="password" id="userPassword">


<label>Role</label>
<select name="role" id="userRole" required>
    <option value="pengguna">Pengguna</option>
</select>

<button type="submit" class="btn btn-primary" id="modalBtn">Simpan</button>
</form>
</div>
</div>

<?php include "../layout/footer.php"; ?>
</div>


<script>
const modal = document.getElementById('userModal');
const title = document.getElementById('modalTitle');
const action = document.getElementById('formAction');
const userId = document.getElementById('userId');
const email = document.getElementById('userEmail');
const password = document.getElementById('userPassword');
const role = document.getElementById('userRole');

/* ================= TAMBAH USER ================= */
function openAddModal() {
    title.innerText = "Tambah User";
    action.value = "add";
    userId.value = "";
    email.value = "";
    password.value = "";
    password.required = true;
    role.value = "pengguna";

    modal.style.display = "flex";
    email.focus();
}

/* ================= EDIT USER ================= */
document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', function () {
        title.innerText = "Edit User";
        action.value = "edit";
        userId.value = this.dataset.id;
        email.value = this.dataset.email;
        role.value = this.dataset.role;

        password.value = "";
        password.required = false;

        modal.style.display = "flex";
        email.focus();
    });
});


function deleteUser(id) {
    if (confirm("Yakin ingin menghapus user ini?")) {
        document.getElementById('deleteId').value = id;
        document.getElementById('deleteForm').submit();
    }
}



/* ================= CLOSE ================= */
function closeModal() {
    modal.style.display = "none";
}

window.onclick = function(e) {
    if (e.target === modal) closeModal();
}
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById('userModal');
    modal.style.display = "none";
});
</script>

<form method="POST" id="deleteForm" style="display:none;">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="id" id="deleteId">
</form>

</body>
</html>
