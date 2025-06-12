<?php
require '../../../includes/session.php'; // Include session handling
require '../../../includes/conn.php'; // Include database connection

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $time_id = mysqli_real_escape_string($conn, $_GET['id']);

    // Check if the time slot exists
    $checkTimeSlot = mysqli_query($conn, "SELECT * FROM tbl_sched_time WHERE time_id = '$time_id'");

    if (mysqli_num_rows($checkTimeSlot) > 0) {
        // If the time slot exists, attempt to delete it
        $deleteTimeSlot = mysqli_query($conn, "DELETE FROM tbl_sched_time WHERE time_id = '$time_id'");

        if ($deleteTimeSlot) {
            // Set a success message if the deletion was successful
            $_SESSION['success'] = "Time slot successfully deleted.";
        } else {
            // Set an error message if the deletion failed
            $_SESSION['error'] = "Failed to delete the time slot. Please try again.";
        }
    } else {
        // Set an error message if the time slot does not exist
        $_SESSION['error'] = "Time slot not found.";
    }
} else {
    // If 'id' parameter is not set, set an error message
    $_SESSION['error'] = "Invalid time slot ID.";
}

// Redirect back to the time slots list page
header('Location: ../list.time.php');
exit();
?>
