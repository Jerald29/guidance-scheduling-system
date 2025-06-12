<?php
require '../../../includes/session.php'; // Include session handling
require '../../../includes/conn.php'; // Include database connection

// Check if the 'delete' parameter is set in the URL
if (isset($_GET['delete'])) {
    $announce_id = mysqli_real_escape_string($conn, $_GET['delete']);

    // Check if the announcement exists
    $checkAnnouncement = mysqli_query($conn, "SELECT * FROM tbl_announce WHERE announce_id = '$announce_id'");

    if (mysqli_num_rows($checkAnnouncement) > 0) {
        // If the announcement exists, attempt to delete it
        $deleteAnnouncement = mysqli_query($conn, "DELETE FROM tbl_announce WHERE announce_id = '$announce_id'");

        if ($deleteAnnouncement) {
            // Set a success message if the deletion was successful
            $_SESSION['success'] = "Announcement successfully deleted.";
        } else {
            // Set an error message if the deletion failed
            $_SESSION['error'] = "Failed to delete the announcement. Please try again.";
        }
    } else {
        // Set an error message if the announcement does not exist
        $_SESSION['error'] = "Announcement not found.";
    }
} else {
    // If 'delete' parameter is not set, set an error message
    $_SESSION['error'] = "Invalid announcement ID.";
}

// Redirect back to the announcements list page
header('Location: ../list.announce.php');
exit();
?>
