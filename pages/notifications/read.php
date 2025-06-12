<?php
require '../../includes/session.php';

if (isset($_POST['notif_id'])) {
    $notif_id = intval($_POST['notif_id']); 
    $user_id = $_SESSION['id']; 

    // Update the is_read status
    $query = "UPDATE tbl_notifications SET is_read = 1 WHERE notif_id = ? AND (admin_id = ? OR student_id = ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'iii', $notif_id, $user_id, $user_id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to mark as read.']);
    }

    mysqli_stmt_close($stmt);
}
?>
