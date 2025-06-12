<?php
include '../../../includes/session.php';

if (isset($_POST['submit'])) {
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_pass = mysqli_real_escape_string($conn, $_POST['password2']);

    if ($password === $confirm_pass) {
        $hashedPwd = password_hash($password, PASSWORD_DEFAULT); // Hash the password

        // Prepare SQL queries based on the role
        if ($_SESSION['role'] === "Student") {
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $query = "UPDATE tbl_students 
                      SET username = '$username', email = '$email', password = '$hashedPwd' 
                      WHERE student_id = '{$_SESSION['id']}'";
        } elseif ($_SESSION['role'] === "Administrator") {
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $query = "UPDATE tbl_admins 
                      SET username = '$username', email = '$email', password = '$hashedPwd' 
                      WHERE admin_id = '{$_SESSION['id']}'";
        } elseif ($_SESSION['role'] === "Super Administrator") {
            $query = "UPDATE tbl_master_key 
                      SET password = '$hashedPwd' 
                      WHERE mk_id = '{$_SESSION['id']}'";
        }

        if (mysqli_query($conn, $query)) {
            $_SESSION['update_success'] = true;
        } else {
            $_SESSION['update_error'] = "Error: " . mysqli_error($conn);
        }
        header("Location: ../edit.account.php");
        exit();
    } else {
        $_SESSION['password_unmatch'] = true;
        header("Location: ../edit.account.php");
        exit();
    }
}
?>
