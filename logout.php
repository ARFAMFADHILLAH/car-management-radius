<?php
session_start();

// Hapus session admin saja
unset($_SESSION['admin_logged_in']);
unset($_SESSION['admin_id']);

// Redirect ke login admin
header("Location: login.php");
exit;
?>

<?php
session_start();

// Hapus session user saja
unset($_SESSION['user_logged_in']);
unset($_SESSION['user_id']);

// Redirect ke halaman login user
header("Location: login_user.php");
exit;
?>
