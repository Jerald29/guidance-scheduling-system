<?php
require '../../../includes/session.php';
require '../../../includes/conn.php';

// Get session ID from the GET request
$session_id = isset($_GET['session_id']) ? intval($_GET['session_id']) : null;

// Check if session ID is valid
if (!$session_id) {
    $_SESSION['errors'][] = "Invalid session ID.";
    header("Location: ../list.session.php");
    exit();
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Sanitize and validate input data
    $session_date = trim($_POST['session_date']);
    $session_number = intval($_POST['session_number']);
    $content = trim($_POST['content']);
    $observation = trim($_POST['observation']);
    $reco_id = intval($_POST['reco_id']);
    $session_others = ($reco_id == 4) ? (isset($_POST['session_others']) ? trim($_POST['session_others']) : null) : null;

    // Check for required fields
    if (
        empty($session_date) || 
        empty($session_number) || 
        empty($content) || 
        empty($observation) || 
        !$reco_id
    ) {
        $_SESSION['errors'][] = "All fields are required.";
        header("Location: ../edit.session.php?session_id=$session_id");
        exit();
    }

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare(
        "UPDATE tbl_session_forms 
         SET session_date = ?, session_number = ?, content = ?, observation = ?, reco_id = ?, session_others = ? 
         WHERE session_id = ?"
    );

    // Bind parameters (ensure session_others is null if "Others" is not selected)
    $stmt->bind_param(
        'sissisi', 
        $session_date, 
        $session_number, 
        $content, 
        $observation, 
        $reco_id, 
        $session_others,  // session_others will be null if not "Others" selected
        $session_id
    );

    // Execute the query and handle success or error
    if ($stmt->execute()) {
        $_SESSION['success'] = "Session updated successfully.";
        header("Location: ../edit.session.php?session_id=$session_id");
    } else {
        $_SESSION['errors'][] = "Failed to update session. Please try again.";
        header("Location: ../edit.session.php?session_id=$session_id");
    }

    $stmt->close(); // Close the prepared statement
} else {
    // Redirect to the session list if the form wasn't submitted
    header("Location: ../list.session.php");
    exit();
}
?>
