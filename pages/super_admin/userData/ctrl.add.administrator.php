<?php
include '../../../includes/session.php';

if (isset($_POST['submit'])) {
    // Check if an image file was uploaded
    if (!empty($_FILES['img']['tmp_name']) && is_uploaded_file($_FILES['img']['tmp_name'])) {
        $img = addslashes(file_get_contents($_FILES['img']['tmp_name']));
    } else {
        $img = ""; // Assign an empty value if no image is uploaded
    }
    
    $admin_fname = mysqli_real_escape_string($conn, $_POST['admin_fname']);
    $admin_mname = mysqli_real_escape_string($conn, $_POST['admin_mname']);
    $admin_lname = mysqli_real_escape_string($conn, $_POST['admin_lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $password2 = mysqli_real_escape_string($conn, $_POST['password2']);

    $emailExists = false;
    $usernameExists = false;

    // Check if email exists
    $checkEmail = mysqli_query($conn, "SELECT * FROM tbl_admins WHERE email = '$email'");
    if (mysqli_num_rows($checkEmail) > 0) {
        $_SESSION['email_error'] = "Email already exists!";
        $emailExists = true;
    }

    // Check if username exists
    $checkUsername = mysqli_query($conn, "SELECT * FROM tbl_admins WHERE username = '$username'");
    if (mysqli_num_rows($checkUsername) > 0) {
        $_SESSION['username_error'] = "Username already exists!";
        $usernameExists = true;
    }

    // If either email or username exists, redirect to show both modals
    if ($emailExists || $usernameExists) {
        header('location: ../add.administrator.php');
        exit();
    }

    // Check if passwords match
    if ($password != $password2) {
        $_SESSION['error'] = "Passwords do not match!";
        header('location: ../add.administrator.php');
        exit();
    }

    // Encrypt the password
    $hashpwd = password_hash($password, PASSWORD_BCRYPT);

    // Insert new admin record
    $insertUser = mysqli_query($conn, "INSERT INTO tbl_admins (img, admin_fname, admin_mname, admin_lname, email, username, password) VALUES ('$img', '$admin_fname', '$admin_mname', '$admin_lname', '$email', '$username', '$hashpwd')");

    if ($insertUser) {
        $_SESSION['success'] = "Administrator added successfully!";
    } else {
        $_SESSION['error'] = "Failed to add administrator.";
    }

    header('location: ../add.administrator.php');
    exit();
}
?>
