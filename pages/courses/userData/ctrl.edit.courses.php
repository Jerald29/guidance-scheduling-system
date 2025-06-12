<?php
require '../../../includes/session.php'; // Include session handling
require '../../../includes/conn.php'; // Include database connection

// Check if course_id and course_name are provided
if (isset($_POST['course_id']) && isset($_POST['course_name']) && isset($_POST['course_abv'])) {
    $course_id = $_POST['course_id'];
    $course_name = $_POST['course_name'];
    $course_abv = $_POST['course_abv'];

    // Prepare and bind
    $sql = "UPDATE tbl_courses SET course_name = ?, course_abv = ? WHERE course_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $course_name, $course_abv, $course_id);    

    if ($stmt->execute()) {
        $_SESSION['success'] = "Program updated successfully.";
    } else {
        $_SESSION['error'] = "Error updating program: " . $stmt->error;
    }
    $stmt->close();
} else {
    $_SESSION['error'] = "Invalid data provided.";
}

// Redirect back to the courses list
header("Location: ../list.courses.php");
exit();
