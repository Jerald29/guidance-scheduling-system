<?php
require '../../includes/session.php';
require '../../includes/conn.php'; 

// Ensure the user is an administrator
if ($_SESSION['role'] != 'Administrator') {
    $_SESSION['error'] = "Unauthorized access.";
    header('location: ../../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id']; // Student selected from the dropdown
    $date = $_POST['date'];
    $time = $_POST['time'];
    $purpose = $_POST['purpose'];

    // Define available time slots between 8 AM and 5 PM
    $timeSlots = ['6:00', '7:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00'];

    // Function to check if the time slot is available
    function isSlotAvailable($conn, $date, $time) {
        $conflictCheck = $conn->prepare("SELECT * FROM tbl_schedules WHERE appointment_date = ? AND appointment_time = ?");
        $conflictCheck->bind_param("ss", $date, $time);
        $conflictCheck->execute();
        $result = $conflictCheck->get_result();
        return $result->num_rows == 0; // Return true if no conflict
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

    // Check for existing schedules for the student on the selected date
    $existingCheck = $conn->prepare("SELECT * FROM tbl_schedules WHERE student_id = ? AND appointment_date = ?");
    $existingCheck->bind_param("is", $student_id, $date);
    $existingCheck->execute();
    $existingResult = $existingCheck->get_result();

    if ($existingResult->num_rows > 0) {
        $_SESSION['error'] = "This student already has a schedule on the selected date.";
    } else {
        // Use backtracking to find an available time slot
        $availableSlot = findAvailableSlot($conn, $date, $timeSlots);

        // If a time slot is found, insert the schedule into the database
        if ($availableSlot) {
            $stmt = $conn->prepare("INSERT INTO tbl_schedules (student_id, appointment_date, appointment_time, purpose, status) VALUES (?, ?, ?, ?, 'pending')");
            $stmt->bind_param("isss", $student_id, $date, $availableSlot, $purpose);

            // Check if the execution is successful
            if ($stmt->execute()) {
                $_SESSION['success'] = "Schedule added successfully at $availableSlot!";
            } else {
                $_SESSION['error'] = "Error adding schedule: " . $stmt->error;
            }

            $stmt->close();
        } else {
            // If no time slot is available, return an error
            $_SESSION['error'] = "No available time slots for the selected date.";
        }
    }

    $existingCheck->close();

    // Redirect to the admin schedule page
    header('location: ../schedules/admin.add.schedule.php');
    exit();
}

?>
