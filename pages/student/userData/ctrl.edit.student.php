<?php
include '../../../includes/session.php';

$student_id = $_GET['student_id'];

if (isset($_POST['submit'])) {
    $student_fname = mysqli_real_escape_string($conn, $_POST['student_fname']);
    $student_mname = mysqli_real_escape_string($conn, $_POST['student_mname']);
    $student_lname = mysqli_real_escape_string($conn, $_POST['student_lname']);
    $lrn = mysqli_real_escape_string($conn, $_POST['lrn']);
    $stud_no = mysqli_real_escape_string($conn, $_POST['stud_no']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $password2 = mysqli_real_escape_string($conn, $_POST['password2']);

    // Validate passwords
    if (empty($password) || empty($password2)) {
        $_SESSION['no_pass'] = true;
        header('location: ../edit.student.php?student_id=' . $student_id);
        exit();
    } elseif ($password != $password2) {
        $_SESSION['password_unmatch'] = true;
        header('location: ../edit.student.php?student_id=' . $student_id);
        exit();
    } else {
        $hashpwd = password_hash($password, PASSWORD_BCRYPT);
    }

    // Update student info
    $updateStudent = mysqli_query($conn, "UPDATE tbl_students SET student_fname = '$student_fname', student_mname = '$student_mname', student_lname = '$student_lname', email = '$email', username = '$username', password = '$hashpwd', lrn = '$lrn', stud_no = '$stud_no' WHERE student_id = '$student_id'");

    // Update image if uploaded
    if (!empty($_FILES['image']['tmp_name'])) {
        $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
        mysqli_query($conn, "UPDATE tbl_students SET img = '$image' WHERE student_id = '$student_id'");
    }

    $_SESSION['success'] = true;
    header('location: ../edit.student.php?student_id=' . $student_id); 
}
?>
