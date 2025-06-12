<?php
require '../../includes/session.php'; 
require '../../includes/conn.php'; 

// Ensure the user is an administrator
if ($_SESSION['role'] != 'Administrator') {
    $_SESSION['error'] = "Unauthorized access.";
    header('location: ../../login.php');
    exit();
}

$studentsQuery = "SELECT student_id, student_fname, student_mname, student_lname 
                  FROM tbl_students 
                  ORDER BY student_lname ASC, student_fname ASC, student_mname ASC";
$studentsResult = $conn->query($studentsQuery);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id']; // Student selected from dropdown
    $date = $_POST['date'];
    $time = $_POST['time'];
    $purpose = $_POST['purpose'];

    // Check for existing schedules for the student on the selected date
    $existingCheck = $conn->prepare("SELECT * FROM tbl_schedules WHERE student_id = ? AND appointment_date = ?");
    $existingCheck->bind_param("is", $student_id, $date);
    $existingCheck->execute();
    $existingResult = $existingCheck->get_result();

    if ($existingResult->num_rows > 0) {
        $_SESSION['error'] = "This student already has a schedule on the selected date.";
    } else {
        // Check for scheduling conflicts (time slots)
        $conflictCheck = $conn->prepare("SELECT * FROM tbl_schedules WHERE appointment_date = ? AND appointment_time = ?");
        $conflictCheck->bind_param("ss", $date, $time);
        $conflictCheck->execute();
        $result = $conflictCheck->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['error'] = "Conflict: The selected time slot is already booked.";
        } else {
            // Prepare the SQL statement to insert a new schedule
            $stmt = $conn->prepare("INSERT INTO tbl_schedules (student_id, appointment_date, appointment_time, purpose, status) VALUES (?, ?, ?, ?, 'confirmed')");
            $stmt->bind_param("isss", $student_id, $date, $time, $purpose);

            // Check if the execution is successful
            if ($stmt->execute()) {
                $_SESSION['success'] = "The schedule was successfully added for the student!";

                // Fetch the schedule_id of the newly added schedule
                $schedule_id = $stmt->insert_id; // Get the auto-incremented schedule_id

                // Insert the notification for the student
                $date = date('F j, Y', strtotime($date)); 
                $time = date('g:i A', strtotime($time));
                $notification_message = "A new schedule has been added for you on $date at $time.";
                $notifStmt = $conn->prepare("INSERT INTO tbl_notifications (student_id, schedule_id, message, role) VALUES (?, ?, ?, 'Student')");
                $notifStmt->bind_param("iis", $student_id, $schedule_id, $notification_message);
                $notifStmt->execute();
                $notifStmt->close();
                
            } else {
                $_SESSION['error'] = "Error adding schedule: " . $stmt->error;
            }

            $stmt->close();
        }

        $conflictCheck->close();
    }

    $existingCheck->close();

    header('location: admin.add.schedule.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Schedule | GCS Bacoor</title>
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
                            <h1 class="m-0">Add Schedule</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
                                <li class="breadcrumb-item active">Add Schedule</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div>
            </div>
            <!-- /.content-header -->

            <?php
            // Display success or error messages
            if (isset($_SESSION['success'])) {
                echo "<script>
                        swal('Success!', '{$_SESSION['success']}', 'success').then(function() {
                            window.location = 'admin.add.schedule.php';
                        });
                      </script>";
                unset($_SESSION['success']);
            } elseif (isset($_SESSION['error'])) {
                echo "<script>
                        swal('Error!', '{$_SESSION['error']}', 'error').then(function() {
                            window.location = 'admin.add.schedule.php';
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
                            <!-- Note for Students -->
                            <div role="alert" class="slide-in" style="font-size: 16px; line-height: 1.5; text-align: justify; background-color: #e9f7fc; border-left: 5px solid #17a2b8; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); border-radius: 5px; padding: 15px;">
                                <strong style="color: #0d6efd;">Reminder:</strong> Before adding a new appointment, please view the 
                                <a href="../schedules/student.calendar.php" class="alert-link" style="color: #0d6efd; font-weight: bold; text-decoration: none; transition: color 0.3s, text-decoration 0.3s;" 
                                onmouseover="this.style.color='#00000'; this.style.textDecoration='underline';" 
                                onmouseout="this.style.color='#0d6efd'; this.style.textDecoration='none';">
                                    Calendar of Scheduled Appointments
                                </a> 
                                to avoid conflicts with existing schedules.
                                <style>
                                    .slide-in {
                                        animation: slideIn 1.5s forwards;
                                    }

                                    @keyframes slideIn {
                                        from {
                                            transform: translateX(-100%); /* Start from the left */
                                            opacity: 0; /* Start invisible */
                                        }
                                        to {
                                            transform: translateX(0); /* End at original position */
                                            opacity: 1; /* Fade in */
                                        }
                                    }
                                </style>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title"><b>Schedule Information</b></h3>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="" id="scheduleForm">
                                    <div class="form-group mb-4">
                                            <label for="student_id">Student</label>
                                            <select name="student_id" class="form-control select2" required>
                                                <option value="">Select Student</option>
                                                <?php while ($student = $studentsResult->fetch_assoc()) { ?>
                                                    <option value="<?php echo $student['student_id']; ?>">
                                                        <?php echo $student['student_lname'] . ", " . $student['student_fname'] . " " . $student['student_mname']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label for="date">Select Appointment Date</label>
                                            <input type="date" name="date" class="form-control" id="date" required>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label for="time">Select Appointment Time</label>
                                            <select id="time" name="time" class="form-control" required>
                                                <option value="">Select Time</option>
                                                <?php
                                                // Include database connection
                                                include 'conn.php';

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

                                                // Close the database connection
                                                mysqli_close($conn);
                                                ?>
                                            </select>
                                        </div>
                                            <div class="form-group mb-4">
                                                <label for="purpose">Reason for Appointment</label>
                                                <textarea name="purpose" class="form-control" id="purpose" rows="3" placeholder="Provide a detailed reason for your appointment" required></textarea>
                                            </div>
                                        <button type="submit" name="submit" class="btn btn-primary">Add Schedule</button>
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
            // Check if the selected date is between Monday and Saturday (no Sundays)
            const dayOfWeek = selectedDate.getDay();
            if (dayOfWeek === 0) { // 0 = Sunday
                swal('Error!', 'Please select an appointment date that falls between Monday and Saturday. Your cooperation is greatly appreciated.', 'error');
                event.preventDefault(); // Prevent form submission
            }
        });
    </script>
    
</body>
</html>
