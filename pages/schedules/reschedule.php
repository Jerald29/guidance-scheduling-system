<?php
require '../../includes/session.php';
require '../../includes/conn.php';

$schedule_id = $_GET['schedule_id'];

// Fetch the schedule details
$schedule_info = mysqli_query($conn, "SELECT * FROM tbl_schedules WHERE schedule_id = '$schedule_id'");

if (!$schedule_info) {
    $_SESSION['error'] = 'Failed to fetch schedule details: ' . mysqli_error($conn);
    header('location: ../reschedule.php'); 
    exit();
}

$schedule = mysqli_fetch_array($schedule_info);
$student_id = $schedule['student_id']; // Get student_id

// Fetch student name
$student_query = $conn->prepare("SELECT student_lname, student_fname FROM tbl_students WHERE student_id = ?");
$student_query->bind_param("i", $student_id);
$student_query->execute();
$student_result = $student_query->get_result();
$student = $student_result->fetch_assoc();

$student_name = $student ? "{$student['student_lname']}, {$student['student_fname']}" : 'Unknown';
$student_query->close();

// Ensure the user is Administrator
if ($_SESSION['role'] != 'Administrator') {
    $_SESSION['error'] = "Unauthorized access.";
    header('location: ../../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $time = $_POST['time'];
    $purpose = $_POST['purpose'];

    // Check for scheduling conflicts
    $conflictCheck = $conn->prepare("SELECT * FROM tbl_schedules WHERE appointment_date = ? AND appointment_time = ? AND schedule_id != ?");
    $conflictCheck->bind_param("ssi", $date, $time, $schedule_id);
    $conflictCheck->execute();
    $result = $conflictCheck->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Conflict: The selected time slot is already booked.";
    } else {
        // Update schedule with new date, time, purpose, and set reschedule flag to 1
        $stmt = $conn->prepare("UPDATE tbl_schedules SET appointment_date = ?, appointment_time = ?, purpose = ?, reschedule = 1 WHERE schedule_id = ?");
        $stmt->bind_param("sssi", $date, $time, $purpose, $schedule_id);
    
        if ($stmt->execute()) {
            // Format the date and time for the notification
            $dateFormatted = date('F j, Y', strtotime($date)); 
            $timeFormatted = date('g:i A', strtotime($time));
    
            // Only insert/update notification if no conflict occurs
            $notifQuery = $conn->prepare("
                INSERT INTO tbl_notifications (student_id, role, message, created_at, is_read, schedule_id)
                VALUES (?, 'Student', ?, NOW(), 0, ?)
                ON DUPLICATE KEY UPDATE message = VALUES(message), created_at = NOW(), is_read = 0
            ");
            $message = "Your schedule has been rescheduled to $dateFormatted at $timeFormatted.";
            $notifQuery->bind_param("isi", $student_id, $message, $schedule_id);
            $notifQuery->execute();
            $notifQuery->close();
    
            $_SESSION['success'] = "Schedule rescheduled successfully!";
        } else {
            $_SESSION['error'] = "Error updating schedule: " . $stmt->error;
        }
    
        $stmt->close();
    }    

    $conflictCheck->close();

    header('location: ../schedules/reschedule.php?schedule_id=' . $schedule_id); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reschedule Appointment | GCS Bacoor</title>
    <link rel="icon" href="../../docs/assets/img/gcs-bac.png">
    <?php include '../../includes/links.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
</head>
<body class="hold-transition layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <?php include '../../includes/navbar.php'; ?>
        <?php include '../../includes/sidebar.php'; ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Reschedule Appointment</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Reschedule Appointment</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            if (isset($_SESSION['success'])) {
                echo "<script>
                        swal('Success!', '{$_SESSION['success']}', 'success').then(function() {
                            window.location = '../schedules/reschedule.php?schedule_id=$schedule_id';
                        });
                      </script>";
                unset($_SESSION['success']);
            } elseif (isset($_SESSION['error'])) {
                echo "<script>
                        swal('Error!', '{$_SESSION['error']}', 'error').then(function() {
                            window.location = '../schedules/reschedule.php?schedule_id=$schedule_id';
                        });
                      </script>";
                unset($_SESSION['error']);
            }
            ?>

            <section class="content">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <!-- Note for Admin -->
                            <div role="alert" class="slide-in" style="font-size: 16px; line-height: 1.5; text-align: justify; background-color: #fff3cd; border-left: 5px solid #ffc107; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); border-radius: 5px; padding: 15px;">
                                <strong style="color: #d39e00;">Rescheduling Reminder:</strong> Please ensure that any rescheduled appointment does not conflict with existing schedules.  
                                Before proceeding, kindly check the  
                                <a href="../schedules/student.calendar.php" class="alert-link" style="color: #d39e00; font-weight: bold; text-decoration: none; transition: color 0.3s, text-decoration 0.3s;" 
                                onmouseover="this.style.color='#00000'; this.style.textDecoration='underline';" 
                                onmouseout="this.style.color='#d39e00'; this.style.textDecoration='none';">
                                    Calendar of Scheduled Appointments.
                                </a> 
                                <style>
                                    .slide-in {
                                        animation: slideIn 1.5s forwards;
                                    }

                                    @keyframes slideIn {
                                        from {
                                            transform: translateX(-100%);
                                            opacity: 0; 
                                        }
                                        to {
                                            transform: translateX(0); 
                                            opacity: 1; 
                                        }
                                    }
                                </style>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Schedule Info</h3>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="" id="scheduleForm">
                                        <div class="form-group mb-4">
                                            <label for="student_name">Student</label>
                                            <input type="text" name="student_name" class="form-control" value="<?php echo $student_name; ?>" readonly>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label for="date">Date</label>
                                            <input type="date" name="date" class="form-control" id="date" value="<?php echo $schedule['appointment_date']; ?>" required min="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                        <div class="form-group mb-4">
                                            <label for="time">Time</label>
                                            <select id="time" name="time" class="form-control" required>
                                                <option value="">Select Time</option>
                                                <?php
                                                $query = "SELECT time_slot FROM tbl_sched_time ORDER BY time_slot";
                                                $result = mysqli_query($conn, $query);
                                                if ($result) {
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        $time = $row['time_slot'];
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
                                            <label for="purpose">Reason of the Student</label>
                                            <textarea name="purpose" class="form-control" id="purpose" rows="3" readonly><?php echo $schedule['purpose']; ?></textarea>
                                        </div>
                                        <button type="submit" name="submit" class="btn btn-primary">Reschedule</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <?php include '../../includes/footer.php'; ?>
    </div>
    <?php include '../../includes/script.php'; ?>
</body>
</html>
<?php
$conn->close();
?>
