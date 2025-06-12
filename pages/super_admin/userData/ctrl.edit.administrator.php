<?php
require '../../../includes/session.php';
require '../../../includes/conn.php'; 

$admin_id = $_GET['admin_id'];

// Handle image upload and form submission
if (isset($_POST['submit'])) {
    $alert_message = '';
    $alert_class = 'alert-success';

    // Handle image upload
    if (!empty($_FILES['image']['tmp_name'])) {
        $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
        $image_update = mysqli_query($conn, "UPDATE tbl_admins SET img = '$image' WHERE admin_id = '$admin_id'");
        if ($image_update) {
            $alert_message .= "Profile picture updated successfully.<br>";
        } else {
            $alert_message .= "Error updating profile picture.<br>";
            $alert_class = 'alert-danger';
        }
    }

    // Handle form fields
    $admin_fname = mysqli_real_escape_string($conn, $_POST['admin_fname']);
    $admin_mname = mysqli_real_escape_string($conn, $_POST['admin_mname']);
    $admin_lname = mysqli_real_escape_string($conn, $_POST['admin_lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $password2 = mysqli_real_escape_string($conn, $_POST['password2']);

    // Password handling
    if (!empty($password) && !empty($password2)) {
        if ($password === $password2) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $update = mysqli_query($conn, "UPDATE tbl_admins SET admin_fname='$admin_fname', admin_mname='$admin_mname', admin_lname='$admin_lname', email='$email', username='$username', password='$hashed_password' WHERE admin_id='$admin_id'");
            $alert_message .= "Information updated successfully.";
        } else {
            $alert_message .= "Passwords do not match.";
            $alert_class = 'alert-danger';
        }
    } else {
        $update = mysqli_query($conn, "UPDATE tbl_admins SET admin_fname='$admin_fname', admin_mname='$admin_mname', admin_lname='$admin_lname', email='$email', username='$username' WHERE admin_id='$admin_id'");
        $alert_message .= "Information updated successfully.";
    }

    if (!$update) {
        $alert_message .= "Error updating information.";
        $alert_class = 'alert-danger';
    }

    $_SESSION['alert_message'] = $alert_message;
    $_SESSION['alert_class'] = $alert_class;
    header('Location: ../edit.administrator.php?admin_id=' . $admin_id);
}
?>
