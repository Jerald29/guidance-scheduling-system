<?php
session_start();
ob_start();

include 'conn.php';

if (isset($_SESSION['role'])) {
    
} else {
    header("location: ../login/login.php");
}
?>