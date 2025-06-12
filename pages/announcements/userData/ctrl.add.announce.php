<?php
require '../../../includes/session.php'; // Include session handling
require '../../../includes/conn.php'; // Include database connection

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $announce_content = $_POST['announce_content'];

    // Validate form inputs
    if (empty($announce_content)) {
        $_SESSION['error'] = "Announcement content is required.";
        header("Location: ../add.announce.php");
        exit();
    }

    // Prepare the SQL query to insert the new announcement
    $sql = "INSERT INTO tbl_announce (announce_content, created_at) 
            VALUES (?, NOW())";
    
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        $_SESSION['error'] = "Failed to prepare the query.";
        header("Location: ../add.announce.php");
        exit();
    }

    $stmt->bind_param("s", $announce_content);

    // Execute the query
    if ($stmt->execute()) {
        $_SESSION['success'] = "Announcement added successfully!";
        header("Location: ../list.announce.php");
        exit();
    } else {
        $_SESSION['error'] = "Failed to add announcement. Please try again.";
        header("Location: ../add.announce.php");
        exit();
    }
} else {
    // If the request method is not POST, redirect to the add announcement page
    header("Location: ../add.announce.php");
    exit();
}
?>
