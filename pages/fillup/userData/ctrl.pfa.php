<?php
require '../../../includes/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['id']) && $_SESSION['role'] === 'Student') {
    require '../../../includes/conn.php';

    $student_id = $_SESSION['id'];

    // Get form data
    $date = $_POST['date'];
    $pfa_age = $_POST['pfa_age'];
    $ref_id = $_POST['ref_id'];
    $ref_other = isset($_POST['ref_other']) ? $_POST['ref_other'] : null;
    $nos_id = $_POST['nos_id'];
    $problem_discuss = $_POST['problem_discuss'];
    $counselor_name = $_POST['counselor_name'];

    // Check if a PFA form already exists for this student
    $check_query = "SELECT * FROM tbl_pfa_forms WHERE student_id = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param('i', $student_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Update existing PFA form
        $update_query = "UPDATE tbl_pfa_forms 
                         SET pfa_date = ?, pfa_age = ?, ref_id = ?, ref_other = ?, nos_id = ?, problem_discuss = ?, counselor_name = ?
                         WHERE student_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param('siisissi', $date, $pfa_age, $ref_id, $ref_other, $nos_id, $problem_discuss, $counselor_name, $student_id);

        if ($update_stmt->execute()) {
            $_SESSION['success'] = "PFA form updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update PFA form.";
        }
        $update_stmt->close();
    } else {
        // Insert new PFA form
        $insert_query = "INSERT INTO tbl_pfa_forms (student_id, pfa_date, pfa_age, ref_id, ref_other, nos_id, problem_discuss, counselor_name) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param('isiiisss', $student_id, $date, $pfa_age, $ref_id, $ref_other, $nos_id, $problem_discuss, $counselor_name);

        if ($insert_stmt->execute()) {
            $_SESSION['success'] = "PFA form saved successfully!";
        } else {
            $_SESSION['error'] = "Failed to save PFA form.";
        }
        $insert_stmt->close();
    }

    $check_stmt->close();
    $conn->close();

    // Redirect back to the form
    header("Location: ../pfa.php");
    exit();
} else {
    // If unauthorized access
    $_SESSION['error'] = "Unauthorized action.";
    header("Location: ../dashboard/index.php");
    exit();
}
?>
