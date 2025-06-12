<?php
require '../../../includes/session.php';
require '../../../includes/conn.php'; 

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Fetch student ID from the URL
    $student_id = isset($_GET['student_id']) ? (int)$_GET['student_id'] : null;

    // Validate student ID
    if (!$student_id) {
        $_SESSION['errors'][] = "No student ID provided.";
        header("Location: ../add.session.php?student_id=" . htmlspecialchars($student_id));
        exit();
    }

    // Fetch and sanitize input values
    $session_date = isset($_POST['session_date']) ? htmlspecialchars($_POST['session_date']) : null;
    $session_number = isset($_POST['session_number']) ? (int)$_POST['session_number'] : null;
    $content = isset($_POST['content']) ? htmlspecialchars($_POST['content']) : null;
    $observation = isset($_POST['observation']) ? htmlspecialchars($_POST['observation']) : null;
    $session_others = isset($_POST['session_others']) ? htmlspecialchars($_POST['session_others']) : null;
    $reco_id = isset($_POST['reco_id']) ? (int)$_POST['reco_id'] : null;

    // Validate required fields
    if (!$session_date || !$session_number || !$content || !$observation || !$reco_id) {
        $_SESSION['errors'][] = "All fields are required.";
        header("Location: ../add.session.php?student_id=" . htmlspecialchars($student_id));
        exit();
    }

    $errors = [];

    // Check if session number already exists for this student
    $checkSessionNumber = $conn->prepare("SELECT session_id FROM tbl_session_forms WHERE student_id = ? AND session_number = ?");
    $checkSessionNumber->bind_param("ii", $student_id, $session_number);
    $checkSessionNumber->execute();
    $checkSessionNumber->store_result();
    if ($checkSessionNumber->num_rows > 0) {
        $errors[] = "Session number already exists for this student.";
    }
    $checkSessionNumber->close();

    // Check if session date already exists for this student
    $checkSessionDate = $conn->prepare("SELECT session_id FROM tbl_session_forms WHERE student_id = ? AND session_date = ?");
    $checkSessionDate->bind_param("is", $student_id, $session_date);
    $checkSessionDate->execute();
    $checkSessionDate->store_result();
    if ($checkSessionDate->num_rows > 0) {
        $errors[] = "Session date already exists for this student.";
    }
    $checkSessionDate->close();

    // If there are errors, redirect back with errors
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: ../add.session.php?student_id=" . htmlspecialchars($student_id));
        exit();
    }

    // Insert new session
    $stmt = $conn->prepare("INSERT INTO tbl_session_forms (student_id, session_date, session_number, content, observation, session_others, reco_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssi", $student_id, $session_date, $session_number, $content, $observation, $session_others, $reco_id);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        $_SESSION['success'] = "Session notes added successfully.";
    } else {
        $_SESSION['errors'][] = "Error adding session: " . $stmt->error;
    }

    // Close statement and redirect
    $stmt->close();
    header("Location: ../list.session.php");
    exit();
}

// Close the connection
$conn->close();
?>
