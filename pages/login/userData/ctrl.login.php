<?php
include '../../../includes/conn.php';
ob_start();
session_start();

//check users
if (isset($_POST['submit']) || isset($_SESSION['update_success'])) {

    if (isset($_SESSION['update_success'])) {
        $username = $_SESSION['username'];
        unset($_SESSION['username']);
        $password = $_SESSION['password'];
        unset($_SESSION['password']);

        unset($_SESSION['email']);

    } else {

        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
    }

    $master_key = mysqli_query($conn, "SELECT * FROM tbl_master_key WHERE username = '$username'");
    $numrow_mk = mysqli_num_rows($master_key);
    
    $admin = mysqli_query($conn, "SELECT * FROM tbl_admins WHERE username = '$username'");
    $numrow_acc = mysqli_num_rows($admin);
    
    $student = mysqli_query($conn, "SELECT * FROM tbl_students WHERE username = '$username'");
    $numrow_student = mysqli_num_rows($student);

    $guest = mysqli_query($conn, "SELECT * FROM tbl_guests WHERE username = '$username'");
    $numrow_guest = mysqli_num_rows($guest);

    if ($numrow_mk > 0) {
        $row = mysqli_fetch_array($master_key);
        $hashedpass = password_verify($password, $row['password']);

        if ($hashedpass == true) {
            $_SESSION['role'] = "Super Administrator";
            $_SESSION['id'] = $row['mk_id'];
            $_SESSION['name'] = $row['name'];

            header("location: ../../dashboard/index.php");

        } else {
            $_SESSION['password_incorrect'] = true;
            header("location: ../login.php");
        }     

    } elseif ($numrow_acc > 0) {
        $row = mysqli_fetch_array($admin);
        $hashedpass = password_verify($password, $row['password']);

        if ($hashedpass == true) {
            $_SESSION['role'] = "Administrator";
            $_SESSION['id'] = $row['admin_id'];
            $_SESSION['name'] = $row['admin_lname'] . ", " . $row['admin_fname'];

            header("location: ../../dashboard/index.php");

        } else {
            $_SESSION['password_incorrect'] = true;
            header("location: ../login.php");
        }

    } elseif ($numrow_student > 0) {
        $row = mysqli_fetch_array($student);
        $hashedpass = password_verify($password, $row['password']);

        if ($hashedpass == true) {
            $_SESSION['role'] = "Student";
            $_SESSION['id'] = $row['student_id'];
            $_SESSION['name'] = $row['student_lname'] . ", " . $row['student_fname'];

            header("location: ../../dashboard/index.php");

        } else {
            $_SESSION['password_incorrect'] = true;
            header("location: ../login.php");
        }
    
    } elseif ($numrow_guest > 0) {
        $row = mysqli_fetch_array($guest);
        $hashedpass = password_verify($password, $row['password']);

        if ($hashedpass == true) {
            $_SESSION['role'] = "Guest";
            $_SESSION['id'] = $row['guest_id'];
            $_SESSION['name'] = $row['guest_lname'] . ", " . $row['guest_fname'];

            header("location: ../../dashboard/index.php");

        } else {
            $_SESSION['password_incorrect'] = true;
            header("location: ../login.php");
        }

    } else {
        $_SESSION['username_incorrect'] = true;
        header("location: ../login.php");
    }
}
?>