<?php
require '../../includes/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["decline_reschedule"])) {
    $schedule_id = $_POST["schedule_id"];
    $student_id = $_SESSION['id']; // Get student ID from session

    // Decline start
    $query = "UPDATE tbl_schedules SET status = 'cancelled' WHERE schedule_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $schedule_id);

    if ($stmt->execute()) {
        // Fetch student and schedule details
        $scheduleQuery = $conn->prepare("SELECT s.student_lname, s.student_fname, sc.appointment_date, sc.appointment_time 
                                         FROM tbl_students s
                                         JOIN tbl_schedules sc ON s.student_id = sc.student_id 
                                         WHERE sc.schedule_id = ?");
        $scheduleQuery->bind_param("i", $schedule_id);
        $scheduleQuery->execute();
        $scheduleResult = $scheduleQuery->get_result();
        $scheduleData = $scheduleResult->fetch_assoc();
        $scheduleQuery->close();
        // Decline end

        // Format date and time
        $formattedDate = date("F j, Y", strtotime($scheduleData['appointment_date']));
        $formattedTime = date("g:i A", strtotime($scheduleData['appointment_time']));

        // Notif start
        $adminQuery = mysqli_query($conn, "SELECT admin_id FROM tbl_admins");

        while ($adminRow = mysqli_fetch_assoc($adminQuery)) {
            $admin_id = $adminRow['admin_id'];
            $message = "Student: {$scheduleData['student_lname']}, {$scheduleData['student_fname']} has declined the rescheduled appointment on $formattedDate at $formattedTime.";

            // Insert notification with schedule_id, student_id, and admin_id
            $notifStmt = mysqli_prepare($conn, "INSERT INTO tbl_notifications (admin_id, student_id, schedule_id, role, message) VALUES (?, ?, ?, 'Guidance Counselor', ?)");
            mysqli_stmt_bind_param($notifStmt, "iiis", $admin_id, $student_id, $schedule_id, $message);
            mysqli_stmt_execute($notifStmt);
            mysqli_stmt_close($notifStmt);
        }
        // Notif end

        $_SESSION['success'] = "You have declined the rescheduled appointment.";
        header("Location: list.my.schedule.php?success=declined");
        exit();
    } else {
        $_SESSION['error'] = "Error updating schedule.";
        header("Location: list.my.schedule.php?error=update_failed");
        exit();
    }
}

// If accessed directly, redirect
header("Location: list.my.schedule.php");
exit();
