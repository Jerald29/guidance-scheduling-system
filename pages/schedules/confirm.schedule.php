<?php
require '../../includes/session.php';

$confirmation_message = "No schedule ID or action provided.";

// Check if a schedule ID and action are provided via GET request
if (isset($_GET['schedule_id'], $_GET['action'])) {
    $schedule_id = $_GET['schedule_id'];
    $action = $_GET['action'];
    $isUpdateSuccess = false;

    // Prepare SQL statement based on action
    if ($action === 'confirm') {
        $stmt = $conn->prepare("UPDATE tbl_schedules SET status = 'confirmed' WHERE schedule_id = ?");
        $confirmation_message = "Confirmation successful. The schedule is now set as confirmed.";
    } elseif ($action === 'unconfirm') {
        $stmt = $conn->prepare("UPDATE tbl_schedules SET status = 'pending' WHERE schedule_id = ?");
        $confirmation_message = "The schedule has been set to pending and is no longer confirmed.";
    } elseif ($action === 'complete') { // <-- Add "Complete" action here
        $stmt = $conn->prepare("UPDATE tbl_schedules SET status = 'completed' WHERE schedule_id = ?");
        $confirmation_message = "The appointment has been successfully marked as completed.";
    } else {
        $confirmation_message = "Invalid action.";
        header("Location: list.schedule.php");
        exit();
    }

    $stmt->bind_param("i", $schedule_id);
    $isUpdateSuccess = $stmt->execute();

    if ($isUpdateSuccess) {
        // Fetch schedule details for notification
        $scheduleQuery = $conn->prepare("SELECT appointment_date, appointment_time, student_id FROM tbl_schedules WHERE schedule_id = ?");
        $scheduleQuery->bind_param("i", $schedule_id);
        $scheduleQuery->execute();
        $scheduleResult = $scheduleQuery->get_result();

        if ($scheduleResult->num_rows > 0) {
            $schedule = $scheduleResult->fetch_assoc();
            $appointment_date = date('F j, Y', strtotime($schedule['appointment_date']));
            $appointment_time = date('g:i A', strtotime($schedule['appointment_time']));
            $student_id = $schedule['student_id'];

            // Set notification message based on action
            if ($action === 'confirm') {
                $notification_message = "Great news! Your schedule for $appointment_date at $appointment_time is confirmed.";
            } elseif ($action === 'unconfirm') {
                $notification_message = "Your schedule for $appointment_date at $appointment_time is currently unconfirmed.";
            } elseif ($action === 'complete') { // <-- Add notification for "Complete" action
                $notification_message = "Your appointment on $appointment_date at $appointment_time has been marked as completed.";
            }

            // Insert notification for Student
            $insertNotification = $conn->prepare("INSERT INTO tbl_notifications (student_id, schedule_id, role, message) VALUES (?, ?, 'Student', ?)");
            $insertNotification->bind_param("iis", $student_id, $schedule_id, $notification_message);
            $insertNotification->execute();
            $insertNotification->close();
        }
    } else {
        $confirmation_message = "Error updating schedule.";
    }

    $stmt->close();
}
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Schedule Result | GCS Bacoor</title>

    <?php include '../../includes/links.php'; ?>
    
    <!-- Add SweetAlert CSS and JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
</head>

<body class="hold-transition layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">

        <?php include '../../includes/navbar.php'; ?>
        <?php include '../../includes/sidebar.php'; ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"></h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header text-center">
                                    <h3 class="card-title">Confirmation Result</h3>
                                </div>
                                <div class="card-body text-center">
                                    <div id="confirmation-message">
                                        <h4><?= $confirmation_message; ?></h4> 
                                    </div>
                                    <a href="../../index.php" class="btn btn-secondary bg-teal mt-3 mx-3">Back to Dashboard</a>
                                    <a href="list.schedule.php" class="btn btn-primary mt-3 mx-3">Back to Schedule List</a>
                                    <a href="../sessions/list.session.php" class="btn btn-success mt-3 mx-3">Session Notes</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <?php include '../../includes/footer.php'; ?>

        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>

    <?php include '../../includes/script.php'; ?>

    <script>
        const actionMessage = '<?= strpos($confirmation_message, "Error") === false ? "Success!" : "Error!" ?>';
        const sweetAlertMessage = 
            '<?= ($action === "confirm") ? "Schedule confirmed!" : 
                (($action === "unconfirm") ? "Schedule unconfirmed!" : 
                "Appointment completed!"); ?>';
        
        swal(actionMessage, sweetAlertMessage, '<?= strpos($confirmation_message, "Error") === false ? "success" : "error" ?>').then(() => {
            window.location = 'list.schedule.php';
        });
    </script>

</body>

</html>
