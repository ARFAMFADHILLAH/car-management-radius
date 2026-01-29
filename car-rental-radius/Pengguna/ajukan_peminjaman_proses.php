<?php
session_start();
require '../functions.php';

$users_id = $_SESSION['user_id'];
$user_name = $_SESSION['email'];

$division = $_POST['division'];
$phone = $_POST['phone'];
$car_id = $_POST['car_id'];
$start = $_POST['start_datetime'];
$end = $_POST['end_datetime'];
$purpose = $_POST['purpose'];
$destination = $_POST['destination'];
$customer_name = $_POST['customer_name'];
$notes = $_POST['notes'];

$sql = "INSERT INTO car_requests
(
    users_id,
    user_name,
    division,
    phone,
    car_id,
    start_datetime,
    end_datetime,
    purpose,
    destination,
    customer_name,
    notes,
    status,
    created_at
)
VALUES
(
    '$users_id',
    '$user_name',
    '$division',
    '$phone',
    '$car_id',
    '$start',
    '$end',
    '$purpose',
    '$destination',
    '$customer_name',
    '$notes',
    'pending',
    NOW()
)";

mysqli_query($conn, $sql);

header("Location: riwayat_pengajuan.php");
