<?php
include '../../../includes/session.php';
include '../../../includes/conn.php'; 

if (isset($_GET['session_id'])) {
    $session_id = mysqli_real_escape_string($conn, $_GET['session_id']);

    // Fetch student_id before deleting the session
    $query = mysqli_query($conn, "SELECT student_id FROM tbl_session_forms WHERE session_id = '$session_id'");
    $row = mysqli_fetch_assoc($query);
    
    if ($row) {
        $student_id = $row['student_id'];

        // Proceed with deletion
        $deleteSession = mysqli_query($conn, "DELETE FROM tbl_session_forms WHERE session_id = '$session_id'");

        if ($deleteSession) {
            $_SESSION['success'] = "Session notes successfully deleted.";
        } else {
            $_SESSION['error'] = "Failed to delete session. Please try again.";
        }

        header("Location: ../history.session.php?student_id=$student_id");
        exit();
    } else {
        $_SESSION['error'] = "Session not found.";
        header("Location: ../list.session.php");
        exit();
    }
}
?>
