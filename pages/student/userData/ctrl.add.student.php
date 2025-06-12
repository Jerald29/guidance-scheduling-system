<?php
include '../../../includes/session.php';

if (isset($_POST['submit'])) {

    // Check if an image file was uploaded
    if (!empty($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
    } else {
        $image = ""; // Assign an empty value if no image is uploaded
    }
    
    $student_fname = mysqli_real_escape_string($conn, $_POST['student_fname']);
    $student_mname = mysqli_real_escape_string($conn, $_POST['student_mname']);
    $student_lname = mysqli_real_escape_string($conn, $_POST['student_lname']);
    $lrn = mysqli_real_escape_string($conn, $_POST['lrn']);
    $stud_no = mysqli_real_escape_string($conn, $_POST['stud_no']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $password2 = mysqli_real_escape_string($conn, $_POST['password2']);

    $emailExists = false;
    $usernameExists = false;
    $lrnExists = false;
    $studNoExists = false;

    // Check if email exists
    $checkEmail = mysqli_query($conn, "SELECT * FROM tbl_students WHERE email = '$email'");
    if (mysqli_num_rows($checkEmail) > 0) {
        $_SESSION['email_error'] = "Email already exists!";
        $emailExists = true;
    }

    // Check if username exists
    $checkUsername = mysqli_query($conn, "SELECT * FROM tbl_students WHERE username = '$username'");
    if (mysqli_num_rows($checkUsername) > 0) {
        $_SESSION['username_error'] = "Username already exists!";
        $usernameExists = true;
    }

    // Check if LRN exists
    $checkLRN = mysqli_query($conn, "SELECT * FROM tbl_students WHERE lrn = '$lrn'");
    if (mysqli_num_rows($checkLRN) > 0) {
        $_SESSION['lrn_error'] = "LRN already exists!";
        $lrnExists = true;
    }

    // Check if Student Number exists
    $checkStudNo = mysqli_query($conn, "SELECT * FROM tbl_students WHERE stud_no = '$stud_no'");
    if (mysqli_num_rows($checkStudNo) > 0) {
        $_SESSION['stud_no_error'] = "Student Number already exists!";
        $studNoExists = true;
    }

    // If any field exists, show the appropriate modals and stop execution
    if ($emailExists || $usernameExists || $lrnExists || $studNoExists) {
        header('location: ../add.student.php');
        exit();
    }

    // Check if passwords match
    if ($password != $password2) {
        $_SESSION['error'] = "Passwords do not match!";
        header('location: ../add.student.php');
        exit();
    }

    // Encrypt the password
    $hashpwd = password_hash($password, PASSWORD_BCRYPT);

    // Insert new student record
    $insertUser = mysqli_query($conn, "INSERT INTO tbl_students (img, student_fname, student_mname, student_lname, stud_no, lrn, username, password, email) VALUES ('$image', '$student_fname', '$student_mname', '$student_lname', '$stud_no', '$lrn', '$username', '$hashpwd', '$email')");

    if ($insertUser) {
        $_SESSION['success'] = "Student added successfully!";
    } else {
        $_SESSION['error'] = "Failed to add student.";
    }

    header('location: ../add.student.php');
    exit();
}
?>
