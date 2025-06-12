<?php
include '../../../includes/session.php';
include '../../../includes/conn.php'; // Include database connection

if (isset($_POST['submit'])) {
    $student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : null; // Ensure student_id is valid

    if (!$student_id) {
        $_SESSION['error'] = "No student ID provided.";
        header("Location: ../add.eval.php");
        exit();
    }

    // Validate and escape input data
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $exam = mysqli_real_escape_string($conn, $_POST['exam']);
    $test = mysqli_real_escape_string($conn, $_POST['test']);
    $result = mysqli_real_escape_string($conn, $_POST['result']);
    // Remove the escape for description to preserve formatting
    $description = $_POST['description'];

    // Prepare and execute the check for existing evaluations
    $stmt = $conn->prepare("SELECT * FROM tbl_evaluations WHERE student_id = ? AND date = ? AND test = ?");
    $stmt->bind_param("iss", $student_id, $date, $test);
    $stmt->execute();
    $result_eval = $stmt->get_result()->num_rows;
    $stmt->close();

    if ($result_eval > 0) {
        // Evaluation already exists
        $_SESSION['eval_exists'] = "Evaluation for this date and test already exists.";
        header("Location: ../add.eval.php?student_id=$student_id");
        exit();
    } else {
        // Insert new evaluation
        $stmt = $conn->prepare("INSERT INTO tbl_evaluations (student_id, date, exam, test, result, description) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $student_id, $date, $exam, $test, $result, $description);
        
        if ($stmt->execute()) {
            // Success
            $_SESSION['success'] = "Evaluation added successfully.";
        } else {
            // Error
            $_SESSION['error'] = "Failed to add evaluation. Please try again.";
        }
        $stmt->close();
        
        header("Location: ../add.eval.php?student_id=$student_id");
        exit();
    }
} else {
    // Redirect to the form if the submit button wasn't pressed
    header('Location: ../add.eval.php');
    exit();
}
