<?php
include '../../../includes/session.php';
require '../../../includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the student ID from the session
    $student_id = $_SESSION['id'];
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $time = mysqli_real_escape_string($conn, $_POST['time']);
    $purpose = mysqli_real_escape_string($conn, $_POST['purpose']);

    // Function to check if the time slot is available
    function isSlotAvailable($conn, $date, $time) {
        $conflictCheck = mysqli_query($conn, "SELECT * FROM tbl_schedules WHERE appointment_date = '$date' AND appointment_time = '$time'");
        return mysqli_num_rows($conflictCheck) == 0; // Return true if no conflict
    }

    // Backtracking function to find the available time slot
    function findAvailableSlot($conn, $date, $timeSlots, $index = 0) {
        // Base case: if we have checked all time slots and none are available
        if ($index >= count($timeSlots)) {
            return false; // No available slots
        }

        $time = $timeSlots[$index];

        // Check if the time slot is available
        if (isSlotAvailable($conn, $date, $time)) {
            return $time; // Return the available time slot
        }

        // Recursive case: move to the next time slot
        return findAvailableSlot($conn, $date, $timeSlots, $index + 1);
    }

    // Check for existing schedule for the student on the selected date
    $existingCheck = mysqli_query($conn, "SELECT * FROM tbl_schedules WHERE student_id = '$student_id' AND appointment_date = '$date'");

    // If an existing schedule is found for that day
    if (mysqli_num_rows($existingCheck) > 0) {
        // Set session error and return JSON response with error message
        $_SESSION['error'] = "You can only add one schedule per day.";
        echo json_encode(['status' => 'error', 'message' => 'You can only add one schedule per day.']);
    } else {
        // Use backtracking to find an available time slot
        $availableSlot = findAvailableSlot($conn, $date, $timeSlots);

        // If a time slot is found, insert the schedule into the database
        if ($availableSlot) {
            $insertSchedule = mysqli_query($conn, "INSERT INTO tbl_schedules (student_id, appointment_date, appointment_time, purpose, status) VALUES ('$student_id', '$date', '$availableSlot', '$purpose', 'pending')");

            if ($insertSchedule) {
                // Set session success message and return JSON response with success message
                $_SESSION['success'] = "Schedule added successfully at $availableSlot!";
                echo json_encode(['status' => 'success', 'message' => "Schedule added successfully at $availableSlot!"]);
            } else {
                // If there's an error during insertion, set session error and return JSON response
                $_SESSION['error'] = "Error adding schedule.";
                echo json_encode(['status' => 'error', 'message' => 'Error adding schedule.']);
            }
        } else {
            // If no time slot is available, return an error
            $_SESSION['error'] = "No available time slots for the selected date.";
            echo json_encode(['status' => 'error', 'message' => 'No available time slots for the selected date.']);
        }
    }

    // Close the existing schedule check result set
    mysqli_free_result($existingCheck);
}

// Close the database connection
$conn->close();
?>
