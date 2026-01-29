<?php
session_start();
require '../functions.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id'], $_GET['action'])) {
    header("Location: approval_list.php");
    exit;
}

$id = (int) $_GET['id'];
$action = $_GET['action'];
$admin_name = $_SESSION['username']; // admin yang approve

// Validasi data pengajuan
$cek = mysqli_query($conn, "SELECT * FROM car_requests WHERE id = $id");
if (mysqli_num_rows($cek) == 0) {
    header("Location: approval_list.php");
    exit;
}

// ===============================
// ACTION APPROVE
// ===============================
if ($action === 'approve') {

    $query = "
        UPDATE car_requests
        SET 
            status = 'approved',
            approved_by = '$admin_name',
            approved_at = NOW()
        WHERE id = $id
    ";

    mysqli_query($conn, $query);

    header("Location: approval_list.php?msg=approved");
    exit;
}

// ===============================
// ACTION REJECT
// ===============================
if ($action === 'reject') {

    $query = "
        UPDATE car_requests
        SET 
            status = 'rejected',
            approved_by = '$admin_name',
            approved_at = NOW()
        WHERE id = $id
    ";

    mysqli_query($conn, $query);

    header("Location: approval_list.php?msg=rejected");
    exit;
}

// Kalau action tidak valid
header("Location: approval_list.php");
exit;
