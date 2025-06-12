<?php
require '../../../includes/session.php'; // Include session handling
require '../../../includes/conn.php'; // Include database connection

// Ensure the user is an Administrator
if ($_SESSION['role'] != 'Administrator') {
    $_SESSION['error'] = "Unauthorized access.";
    header('Location: ../list.time.php');
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get values from the form
    $time_id = $_POST['time_id'];
    $time_slot = trim($_POST['time_slot']);

    // Validate input
    if (empty($time_slot)) {
        $_SESSION['error'] = "Time slot cannot be empty!";
        header("Location: ../edit.time.php?id=$time_id");
        exit();
    }

    // Check if the updated time slot already exists (excluding the current record)
    $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_sched_time WHERE time_slot = ? AND time_id != ?");
    $stmt->bind_param("si", $time_slot, $time_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        $_SESSION['error'] = "This time slot already exists. Please choose a different time.";
        header("Location: ../edit.time.php?id=$time_id");
        exit();
    }

    // Update the time slot in the database
    $stmt = $conn->prepare("UPDATE tbl_sched_time SET time_slot = ? WHERE time_id = ?");
    $stmt->bind_param("si", $time_slot, $time_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Time slot updated successfully!";
        header("Location: ../list.time.php");
    } else {
        $_SESSION['error'] = "Error updating time slot. Please try again.";
        header("Location: ../edit.time.php?id=$time_id");
    }

    $stmt->close();
    exit();
}

// If accessed without POST request, redirect back
$_SESSION['error'] = "Invalid request!";
header("Location: ../list.time.php");
exit();
?>
