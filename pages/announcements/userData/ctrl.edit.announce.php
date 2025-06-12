<?php
require '../../../includes/session.php'; // Include session handling
require '../../../includes/conn.php'; // Include database connection

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the values from the form
    $announce_id = $_POST['announce_id'];
    $announce_content = $_POST['announce_content'];

    // Validate input
    if (empty($announce_content)) {
        $_SESSION['error'] = "Announcement content cannot be empty!";
        header("Location: ../edit.announce.php?id=$announce_id");
        exit();
    }

    // Update the announcement in the database
    $sql = "UPDATE tbl_announce SET announce_content = ?, created_at = NOW() WHERE announce_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $announce_content, $announce_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Announcement updated successfully!";
        header("Location: ../list.announce.php");
    } else {
        $_SESSION['error'] = "Error updating announcement. Please try again.";
        header("Location: ../edit.announce.php?id=$announce_id");
    }

    exit();
}
?>
