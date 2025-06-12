<?php
include '../../../includes/session.php';

$guest_id = $_GET['guest_id'];

mysqli_query($conn, "DELETE FROM tbl_guests WHERE guest_id = '$guest_id'") or die(mysqli_error($conn));
$_SESSION['success'] = true;
header('location: ../list.guests.php');
