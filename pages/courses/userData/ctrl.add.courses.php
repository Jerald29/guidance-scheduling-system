<?php
require '../../../includes/session.php';
require '../../../includes/conn.php'; // Include your database connection file

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the course name from the POST request
    $course_name = isset($_POST['course_name']) ? trim($_POST['course_name']) : '';
    $course_abv = isset($_POST['course_abv']) ? trim($_POST['course_abv']) : '';

    // Validate the course name
    if (empty($course_name)) {
        $_SESSION['errors'][] = "Course name is required.";
        header("Location: ../add.courses.php"); // Redirect back to the add course form
        exit();
    }

    // Prepare and execute the insert statement
    $stmt = $conn->prepare("INSERT INTO tbl_courses (course_name, course_abv) VALUES (?, ?)");
    $stmt->bind_param("ss", $course_name, $course_abv);    
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Program added successfully.";
    } else {
        $_SESSION['errors'][] = "Failed to add course. Please try again.";
    }
    
    $stmt->close();
    $conn->close(); // Close the database connection
    header("Location: ../list.courses.php"); // Redirect to the course list
    exit();
}

// If accessed without a POST request, redirect to the add course page
header("Location: ../add.courses.php");
exit();
?>
