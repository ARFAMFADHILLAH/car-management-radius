<?php
session_start();
require '../functions.php';


$request_id = $_POST['request_id'];
$car_id = $_POST['car_id'];

// Update status request
mysqli_query($conn, "
    UPDATE car_requests 
    SET status = 'returned'
    WHERE id = '$request_id'
");

// Update status mobil
mysqli_query($conn, "
    UPDATE cars 
    SET active = 1
    WHERE id = '$car_id'
");

header("Location: approval_pengembalian.php?success=1");
exit;
