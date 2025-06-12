<?php
include '../../../includes/session.php';

$admin_id = $_GET['admin_id'];

mysqli_query($conn, "DELETE FROM tbl_admins WHERE admin_id = '$admin_id'") or die(mysqli_error($conn));
$_SESSION['success'] = true;
header('location: ../list.administrator.php');
