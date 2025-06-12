<?php
include '../../../includes/session.php';

if (isset($_POST['submit'])) {

    $img = addslashes(file_get_contents($_FILES['image']['tmp_name']));
    $guest_fname = mysqli_real_escape_string($conn, $_POST['guest_fname']);
    $guest_mname = mysqli_real_escape_string($conn, $_POST['guest_mname']);
    $guest_lname = mysqli_real_escape_string($conn, $_POST['guest_lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $password2 = mysqli_real_escape_string($conn, $_POST['password2']);

    if ($password != $password2) {
        $_SESSION['password_unmatch'] = true;
        header('location: ../add.guestistrator.php');
    } else {
        $hashpwd = password_hash($password, PASSWORD_BCRYPT);
        $insertUser = mysqli_query($conn, "INSERT INTO tbl_guests (img, guest_fname, guest_mname, guest_lname, activation_code, email, username, password) VALUES ('$img', '$guest_fname', '$guest_mname', '$guest_lname', '', '$email', '$username', '$hashpwd')");
        $_SESSION['success'] = true;
        header('location: ../add.guest.php');
    }
}