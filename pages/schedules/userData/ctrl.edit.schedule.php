<?php
require '../../../includes/session.php'; // Include session handling
require '../../../includes/conn.php'; // Include database connection

$schedule_id = $_GET['schedule_id']; // Get the schedule ID from the URL

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize input data
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $time = mysqli_real_escape_string($conn, $_POST['time']);
    $purpose = mysqli_real_escape_string($conn, $_POST['purpose']);

    // Validate input data
    if (empty($date) || empty($time) || empty($purpose)) {
        $_SESSION['error'] = 'All fields are required.';
        header('location: ../edit.schedule.php?schedule_id=' . $schedule_id);
        exit();
    }

    // Check for scheduling conflicts, excluding the current schedule being edited
    $conflictCheck = mysqli_query($conn, "SELECT * FROM tbl_schedules WHERE appointment_date = '$date' AND appointment_time = '$time' AND schedule_id != '$schedule_id'");

    if (mysqli_num_rows($conflictCheck) > 0) {
        $_SESSION['error'] = "Conflict: The selected time slot is already booked.";
        header('location: ../edit.schedule.php?schedule_id=' . $schedule_id);
        exit();
    }

    // Prepare and execute the update statement
    $updateSchedule = mysqli_query($conn, "UPDATE tbl_schedules SET appointment_date = '$date', appointment_time = '$time', purpose = '$purpose' WHERE schedule_id = '$schedule_id'");

    if ($updateSchedule) {
        $_SESSION['success'] = 'Schedule updated successfully.';
    } else {
        $_SESSION['error'] = 'Failed to update schedule. Please try again. Error: ' . mysqli_error($conn); // Include the error message
    }

    // Redirect to the edit page
    header('location: ../edit.schedule.php?schedule_id=' . $schedule_id);
    exit();
}
?>
