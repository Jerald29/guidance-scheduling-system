<?php
include '../../../includes/session.php'; // Include session handling
include '../../../includes/conn.php'; // Include database connection

if (isset($_GET['schedule_id'])) {
    // Sanitize input to prevent SQL injection
    $schedule_id = mysqli_real_escape_string($conn, $_GET['schedule_id']); 

    // Prepare and execute the delete query
    $stmt = $conn->prepare("DELETE FROM tbl_schedules WHERE schedule_id = ?");
    $stmt->bind_param("i", $schedule_id); // Bind the schedule ID as an integer

    // Execute the statement and check the result
    if ($stmt->execute()) {
        // Set success message and trigger a Bootstrap success alert in the redirected page
        $_SESSION['success'] = "Schedule deleted successfully!";
    } else {
        // Set error message and trigger a Bootstrap error alert in the redirected page
        $_SESSION['error'] = "Error deleting schedule.";
    }

    $stmt->close(); // Close the prepared statement
} else {
    // Set error message if no schedule ID is provided
    $_SESSION['error'] = "No schedule ID provided.";
}

// Close the database connection
$conn->close();

// Redirect to the 'list.my.schedule.php' page for students
header('location: ../list.my.schedule.php');
exit(); // Ensure no further code is executed after redirect
?>
