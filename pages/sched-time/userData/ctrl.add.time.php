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
    $time_slot = trim($_POST['time_slot']);

    // Validate input
    if (empty($time_slot)) {
        $_SESSION['error'] = "Time slot cannot be empty!";
        header("Location: ../add.time.php");
        exit();
    }

    // Check if the time slot already exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_sched_time WHERE time_slot = ?");
    $stmt->bind_param("s", $time_slot);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        $_SESSION['error'] = "This time slot already exists. Please choose a different time.";
        header("Location: ../add.time.php");
        exit();
    }

    // Insert the time slot into the database
    $stmt = $conn->prepare("INSERT INTO tbl_sched_time (time_slot) VALUES (?)");
    $stmt->bind_param("s", $time_slot);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Time slot added successfully!";
        header("Location: ../list.time.php");
    } else {
        $_SESSION['error'] = "Error adding time slot. Please try again.";
        header("Location: ../add.time.php");
    }

    $stmt->close();
    exit();
}

// If accessed without POST request, redirect back
$_SESSION['error'] = "Invalid request!";
header("Location: ../list.time.php");
exit();
?>
