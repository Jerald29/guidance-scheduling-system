<?php
include '../../../includes/session.php';

$guest_id = $_GET['guest_id'];

if (isset($_POST['upload'])) {
    if (empty($_FILES['image']['tmp_name'])) {
        $_SESSION['no_image'] = true;
        header('location: ../edit.guest.php?guest_id=' . $guest_id);
    } else {
        $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
        $set_userInfo = mysqli_query($conn, "UPDATE tbl_guests SET img = '$image' WHERE guest_id = '$guest_id'");
        $_SESSION['success'] = true;
        header('location: ../edit.guest.php?guest_id=' . $guest_id);
    }
} elseif (isset($_POST['submit'])) {
    $guest_fname = mysqli_real_escape_string($conn, $_POST['guest_fname']);
    $guest_mname = mysqli_real_escape_string($conn, $_POST['guest_mname']);
    $guest_lname = mysqli_real_escape_string($conn, $_POST['guest_lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $password2 = mysqli_real_escape_string($conn, $_POST['password2']);

    if (empty($password) && empty($password2)) {
        $_SESSION['no_pass'] = true;
        header('location: ../edit.guest.php?guest_id=' . $guest_id);
    } else {
        if ($password != $password2) {
            $_SESSION['password_unmatch'] = true;
            header('location: ../edit.guest.php?guest_id=' . $guest_id);
        } else {
            $hashpwd = password_hash($password, PASSWORD_BCRYPT);
            $updateguest = mysqli_query($conn, "UPDATE tbl_guests SET guest_fname = '$guest_fname', guest_mname = '$guest_mname', guest_lname = '$guest_lname', email = '$email', username = '$username', password = '$hashpwd' WHERE guest_id = '$guest_id'");
            $_SESSION['success'] = true;
            header('location: ../edit.guest.php?guest_id=' . $guest_id);
        }
    }
}
?>
