<?php
include '../../../includes/session.php';
require '../../../includes/conn.php'; // Ensure this file includes the database connection

if (isset($_POST['submit'])) {
    // Get and validate the evaluation ID
    $eval_id = isset($_GET['eval_id']) ? intval($_GET['eval_id']) : null;

    if (!$eval_id) {
        $_SESSION['errors'][] = "Invalid evaluation ID.";
        header('Location: ../evaluations.php');
        exit();
    }

    // Validate and sanitize input data
    $date = trim($_POST['date']);
    $exam = trim($_POST['exam']);
    $test = trim($_POST['test']);
    $result = trim($_POST['result']);
    $description = trim($_POST['description']);

    // Check if all required fields are filled
    if (empty($date) || empty($exam) || empty($test) || empty($result) || empty($description)) {
        $_SESSION['errors'][] = "All fields are required.";
        header("Location: ../edit.eval.php?eval_id=$eval_id");
        exit();
    }

    // Prepare the update statement to prevent SQL injection
    $stmt = $conn->prepare("UPDATE tbl_evaluations SET date = ?, exam = ?, test = ?, result = ?, description = ? WHERE eval_id = ?");
    $stmt->bind_param('sssssi', $date, $exam, $test, $result, $description, $eval_id);

    // Execute the statement and handle success or failure
    if ($stmt->execute()) {
        // Set success message and redirect back to the edit page
        $_SESSION['success'] = "Evaluation updated successfully.";
        header("Location: ../edit.eval.php?eval_id=$eval_id");
        exit(); // Ensure no further code is executed after redirection
    } else {
        // Set error message if update failed and redirect back to the edit page
        $_SESSION['errors'][] = "Failed to update evaluation. Please try again.";
        header("Location: ../edit.eval.php?eval_id=$eval_id");
        exit(); // Ensure no further code is executed after redirection
    }

    $stmt->close(); // Close the prepared statement
} else {
    // If the form is not submitted, redirect back to the evaluations page
    header('Location: ../list.eval.php');
    exit(); // Ensure no further code is executed after redirection
}
?>
