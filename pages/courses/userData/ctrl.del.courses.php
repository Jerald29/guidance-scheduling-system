<?php
include '../../../includes/session.php'; // Include session handling
include '../../../includes/conn.php'; // Include database connection

if (isset($_GET['course_id'])) {
    $course_id = mysqli_real_escape_string($conn, $_GET['course_id']);

    // Check if the course exists
    $checkCourse = mysqli_query($conn, "SELECT * FROM tbl_courses WHERE course_id = '$course_id'");

    if (mysqli_num_rows($checkCourse) > 0) {
        // If course exists, delete it
        $deleteCourse = mysqli_query($conn, "DELETE FROM tbl_courses WHERE course_id = '$course_id'");

        if ($deleteCourse) {
            $_SESSION['success'] = "Program successfully deleted.";
        } else {
            $_SESSION['error'] = "Failed to delete program. Please try again.";
        }
    } else {
        // If course doesn't exist, set an error message
        $_SESSION['error'] = "Program not found.";
    }

    header('Location: ../list.courses.php');
    exit();
} else {
    // If course_id is not provided, set an error message
    $_SESSION['error'] = "Invalid course ID.";
    header('Location: ../list.courses.php');
    exit();
}
?>
