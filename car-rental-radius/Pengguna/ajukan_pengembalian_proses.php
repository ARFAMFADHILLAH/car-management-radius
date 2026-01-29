<?php
session_start();
require '../functions.php';

$request_id = $_POST['request_id'];
$return_datetime = $_POST['return_datetime'];
$condition = $_POST['condition_car'];
$km_end = $_POST['km_end'];
$notes = $_POST['return_notes'];

// Update request menjadi 'return_requested'
mysqli_query($conn, "
    UPDATE car_requests SET
        return_datetime = '$return_datetime',
        condition_car = '$condition',
        km_end = '$km_end',
        return_notes = '$notes',
        status = 'returned'
    WHERE id = '$request_id'
");


header("Location: ajukan_pengembalian.php?success=1");
exit;
