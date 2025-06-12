<?php
include '../../../includes/session.php';

if (isset($_GET['eval_id'])) {
    $eval_id = mysqli_real_escape_string($conn, $_GET['eval_id']);

    // Check if the evaluation exists
    $checkEval = mysqli_query($conn, "SELECT * FROM tbl_evaluations WHERE eval_id = '$eval_id'");

    if (mysqli_num_rows($checkEval) > 0) {
        // If evaluation exists, delete it
        $deleteEval = mysqli_query($conn, "DELETE FROM tbl_evaluations WHERE eval_id = '$eval_id'");

        if ($deleteEval) {
            $_SESSION['success'] = "Evaluation successfully deleted.";
        } else {
            $_SESSION['error'] = "Failed to delete evaluation. Please try again.";
        }
    } else {
        // If evaluation doesn't exist, set an error message
        $_SESSION['error'] = "Evaluation not found.";
    }

    header('Location: ../list.eval.php');
    exit();
} else {
    // If eval_id is not provided, set an error message
    $_SESSION['error'] = "Invalid evaluation ID.";
    header('Location: ../list.eval.php');
    exit();
}
?>