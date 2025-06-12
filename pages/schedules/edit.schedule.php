<?php
require '../../includes/session.php';
require '../../includes/conn.php';

$schedule_id = $_GET['schedule_id'];

// Fetch the schedule details from the database using $schedule_id
$schedule_info = mysqli_query($conn, "SELECT * FROM tbl_schedules WHERE schedule_id = '$schedule_id'");

if (!$schedule_info) {
    $_SESSION['error'] = 'Failed to fetch schedule details: ' . mysqli_error($conn);
    header('location: ../edit.schedule.php'); 
    exit();
}

$schedule = mysqli_fetch_array($schedule_info);

// Ensure the user is a student
if ($_SESSION['role'] != 'Student') {
    $_SESSION['error'] = "Unauthorized access.";
    header('location: ../../login.php');
    exit();
}

// Get student details from the session
$student_id = $_SESSION['id'];
$student_lname = $_SESSION['name'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $time = $_POST['time'];
    $purpose = $_POST['purpose'];

    // Check for existing schedules for the student on the selected date, excluding the current schedule
    $existingCheck = $conn->prepare("SELECT * FROM tbl_schedules WHERE student_id = ? AND appointment_date = ? AND schedule_id != ?");
    $existingCheck->bind_param("isi", $student_id, $date, $schedule_id);
    $existingCheck->execute();
    $existingResult = $existingCheck->get_result();

    if ($existingResult->num_rows > 0) {
        $_SESSION['error'] = "You can only add one schedule per day.";
    } else {
        // Check for scheduling conflicts (time slots)
        $conflictCheck = $conn->prepare("SELECT * FROM tbl_schedules WHERE appointment_date = ? AND appointment_time = ? AND schedule_id != ?");
        $conflictCheck->bind_param("ssi", $date, $time, $schedule_id);
        $conflictCheck->execute();
        $result = $conflictCheck->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['error'] = "Conflict: The selected time slot is already booked.";
        } else {
            $stmt = $conn->prepare("UPDATE tbl_schedules SET appointment_date = ?, appointment_time = ?, purpose = ? WHERE schedule_id = ?");
            $stmt->bind_param("sssi", $date, $time, $purpose, $schedule_id);

            // Check if the execution is successful
            if ($stmt->execute()) {
                // Format date, time, and message
                $dateFormatted = date('F j, Y', strtotime($date)); 
                $timeFormatted = date('g:i A', strtotime($time));
                $message = "Student: $student_lname has updated a schedule on $dateFormatted at $timeFormatted.";

                // Update only Guidance Counselor notifications
                $updateNotification = $conn->prepare("UPDATE tbl_notifications SET message = ?, is_read = 0, created_at = NOW() WHERE schedule_id = ? AND role = 'Guidance Counselor'");
                $updateNotification->bind_param("si", $message, $schedule_id);
                $updateNotification->execute();

                // If no rows were updated, insert new notifications for all admins
                if ($updateNotification->affected_rows === 0) {
                    $admins = $conn->query("SELECT admin_id FROM tbl_admins");
                    while ($admin = $admins->fetch_assoc()) {
                        $conn->query("INSERT INTO tbl_notifications (admin_id, student_id, role, message, created_at, is_read, schedule_id) 
                                    VALUES ({$admin['admin_id']}, $student_id, 'Guidance Counselor', '$message', NOW(), 0, $schedule_id)");
                    }
                }
                $updateNotification->close();

                $_SESSION['success'] = "Schedule updated successfully!";
            } else {
                $_SESSION['error'] = "Error updating schedule: " . $stmt->error;
            }

            $stmt->close();
        }

        $conflictCheck->close();
    }

    // Redirect to the same page to display success/error message
    header('location: ../schedules/edit.schedule.php?schedule_id=' . $schedule_id); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit My Appointment | GCS Bacoor</title>
    <link rel="icon" href="../../docs/assets/img/gcs-bac.png">

    <?php include '../../includes/links.php'; ?>
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <!-- SweetAlert JS -->
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
                            <h1 class="m-0">Edit Schedule</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Edit Schedule</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <?php
            // Display success or error messages
            if (isset($_SESSION['success'])) {
                echo "<script>
                        swal('Success!', '{$_SESSION['success']}', 'success').then(function() {
                            window.location = '../schedules/edit.schedule.php?schedule_id=$schedule_id';
                        });
                      </script>";
                unset($_SESSION['success']);
            } elseif (isset($_SESSION['error'])) {
                echo "<script>
                        swal('Error!', '{$_SESSION['error']}', 'error').then(function() {
                            window.location = '../schedules/edit.schedule.php?schedule_id=$schedule_id';
                        });
                      </script>";
                unset($_SESSION['error']);
            }
            ?>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Schedule Info</h3>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="" id="scheduleForm">
                                        <div class="form-group mb-4">
                                            <label for="student_name">Student</label>
                                            <input type="text" name="student_name" class="form-control" value="<?php echo $student_lname; ?>" readonly>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label for="date">Date</label>
                                            <input type="date" name="date" class="form-control" id="date" value="<?php echo $schedule['appointment_date']; ?>" required>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label for="time">Time</label>
                                            <select id="time" name="time" class="form-control" required>
                                                <option value="">Select Time</option>
                                                <?php
                                                // Define available time slots
                                                // Query to fetch time slots from tbl_sched_time
                                                $query = "SELECT time_slot FROM tbl_sched_time ORDER BY time_slot";
                                                $result = mysqli_query($conn, $query);

                                                // Check if the query was successful
                                                if ($result) {
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        $time = $row['time_slot'];

                                                        // Convert time to AM/PM format
                                                        $formattedTime = date("h:i A", strtotime($time));
                                                        
                                                        echo "<option value=\"$time\">$formattedTime</option>";
                                                    }
                                                } else {
                                                    echo "<option value=\"\">Error fetching times</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label for="purpose">Reason for Appointment</label>
                                            <textarea name="purpose" class="form-control" id="purpose" rows="3" required><?php echo $schedule['purpose']; ?></textarea>
                                        </div>
                                        <button type="submit" name="submit" class="btn btn-primary">Update Schedule</button>
                                    </form>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <?php include '../../includes/footer.php'; ?>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <?php include '../../includes/script.php'; ?>

    <script>
        // Client-side validation for past dates
        document.getElementById('scheduleForm').addEventListener('submit', function(event) {
            const dateInput = document.getElementById('date');
            const selectedDate = new Date(dateInput.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0); // Normalize today to start at midnight

            if (selectedDate < today) {
                swal('Error!', 'You cannot select a date in the past.', 'error');
                event.preventDefault(); // Prevent form submission
            }
        });
    </script>
    
</body>
</html>

<?php
$conn->close(); // Close database connection
?>
