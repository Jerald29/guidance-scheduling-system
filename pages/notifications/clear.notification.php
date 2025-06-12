<?php
require '../../includes/session.php';

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Ensure session is started
if (!isset($_SESSION['role']) || !isset($_SESSION['id'])) {
    die("Unauthorized access.");
}

$role = $_SESSION['role'];
$user_id = $_SESSION['id'];

if ($role == "Administrator") {
    $delete_query = "DELETE FROM tbl_notifications WHERE admin_id = ?";

} elseif ($role == "Student") {
    $delete_query = "DELETE FROM tbl_notifications WHERE student_id = ? AND role = 'Student'";

} else {
    die("Unauthorized access.");
}

// Use prepared statement for security
$stmt = mysqli_prepare($conn, $delete_query);
mysqli_stmt_bind_param($stmt, "i", $user_id);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['notification_message'] = "All notifications have been cleared.";
} else {
    echo "Error clearing notifications: " . mysqli_error($conn);
}

mysqli_stmt_close($stmt);

// Redirect with fallback
$redirect_url = $_SERVER['HTTP_REFERER'] ?? '../../index.php';
header("Location: $redirect_url");
exit();
?>
