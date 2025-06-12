<?php
include '../../../includes/session.php'; // Include session handling

if (isset($_GET['schedule_id'])) {
    // Sanitize input to prevent SQL injection
    $schedule_id = mysqli_real_escape_string($conn, $_GET['schedule_id']); 

    // Prepare and execute the delete query
    $stmt = $conn->prepare("DELETE FROM tbl_schedules WHERE schedule_id = ?");
    $stmt->bind_param("i", $schedule_id); // Bind the schedule ID as an integer

    // Execute the statement and check the result
    if ($stmt->execute()) {
        $_SESSION['success'] = "Schedule deleted successfully!"; // Set success message
    } else {
        $_SESSION['error'] = "Error deleting schedule."; // Set error message
    }

    $stmt->close(); // Close the prepared statement
} else {
    $_SESSION['error'] = "No schedule ID provided."; // Set error message if no ID is provided
}

// Close the database connection
$conn->close();

// Redirect to the schedule list page
header('location: ../list.schedule.php'); 
exit(); // Ensure no further code is executed after redirect
?>
