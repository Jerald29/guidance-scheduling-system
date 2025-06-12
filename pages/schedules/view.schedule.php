<?php
require '../../includes/session.php'; // Include session handling
require '../../includes/conn.php'; // Include database connection

// Ensure the user is an administrator
if ($_SESSION['role'] != 'Administrator') {
    $_SESSION['error'] = "Unauthorized access.";
    header('location: ../../login.php');
    exit();
}

// Get the schedule ID from the URL
$schedule_id = isset($_GET['schedule_id']) ? intval($_GET['schedule_id']) : 0;

// Fetch schedule details based on the schedule ID
$sql = "SELECT s.schedule_id, st.student_fname, st.student_lname, st.img, s.appointment_date, s.appointment_time, s.purpose, s.status, s.created_at
        FROM tbl_schedules as s
        LEFT JOIN tbl_students as st ON s.student_id = st.student_id
        WHERE s.schedule_id = ?
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $schedule_id);
$stmt->execute();
$result = $stmt->get_result();
$schedule = $result->fetch_assoc();

// Check if schedule exists
if (!$schedule) {
    $_SESSION['error'] = "Schedule not found.";
    header("Location: list.schedule.php"); // Redirect back to schedule list
    exit();
}

// Clear any success or error messages for this page
$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
$error = isset($_SESSION['error']) ? $_SESSION['error'] : null;

// Clear the session messages after use
unset($_SESSION['success']);
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Appointment | GCS Bacoor</title>
    <link rel="icon" href="../../docs/assets/img/gcs-bac.png">

    <?php include '../../includes/links.php'; ?>
    <link rel="stylesheet" href="../../docs/assets/css/custom.css"> <!-- Custom styles -->
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
                            <h1 class="m-0">View Appointment</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../dashboard/index.php">Home</a></li>
                                <li class="breadcrumb-item active">View Appointment</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="card">
                    <div class="card-header bg-olive text-white">
                        <h3 class="card-title">Appointment Details</h3>
                    </div>
                    <div class="card-body">
                        <!-- Success or error alerts -->
                        <?php if ($success): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success!</strong> <?php echo $success; ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php endif; ?>

                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong> <?php echo $error; ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="text-center">
                                    <!-- Display student image or default image with hover effect -->
                                    <?php
                                    if (!empty($schedule['img'])) {
                                        echo '<img src="data:image/jpeg;base64,' . base64_encode($schedule['img']) . '" class="img-fluid rounded-circle shadow-lg" alt="Student Image" style="transition: transform 0.3s; width: 150px; height: 150px; margin-top: 15px;">'; // Adjust margin-top value as needed
                                    } else {
                                        echo '<img src="../../docs/assets/img/user2.png" class="img-fluid rounded-circle shadow-lg" alt="Default User Image" style="transition: transform 0.3s; width: 150px; height: 150px; margin-top: 15px;">'; // Adjust margin-top value as needed
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <h4><b>Appointment Details</b></h4>
                                <p><strong>Student Name:</strong> <?php echo $schedule['student_lname'] . ', ' . $schedule['student_fname']; ?></p>
                                <p><strong>Requested Date:</strong> <?php echo date("F j, Y", strtotime($schedule['appointment_date'])); ?></p>
                                <p><strong>Requested Time:</strong> <?php echo date("g:i A", strtotime($schedule['appointment_time'])); ?></p>
                                
                                <!-- Expandable box for Reason for Appointment -->
                                <div class="card mb-3 mt-3">
                                    <div id="appointmentReasonHeader" class="card-header bg-olive text-white d-flex justify-content-between align-items-center" 
                                        style="cursor: pointer;" data-toggle="collapse" data-target="#appointmentReason">
                                        <h5 class="mb-0">
                                            <b>Reason for Appointment</b>
                                        </h5>
                                        <i class="fa fa-chevron-down ml-auto arrow-icon"></i>
                                    </div>
                                    <div id="appointmentReason" class="collapse">
                                        <div class="card-body" style="background: #f7f9fc;">
                                            <p class="mb-0"><?php echo nl2br(htmlspecialchars($schedule['purpose'])); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <style>
                                    .arrow-icon {
                                        transition: transform 0.3s ease;
                                    }
                                    .rotate-arrow {
                                        transform: rotate(180deg);
                                    }
                                </style>
                                <script>
                                    document.getElementById("appointmentReasonHeader").addEventListener("click", function() {
                                        const arrowIcon = this.querySelector(".arrow-icon");
                                        arrowIcon.classList.toggle("rotate-arrow");
                                    });
                                </script>

                                <p><strong>Created At:</strong> <?php echo date("F j, Y, g:i A", strtotime($schedule['created_at'])); ?></p>

                                <p><strong>Status:</strong>
                                    <span class="badge 
                                        <?php 
                                            echo ($schedule['status'] == 'confirmed') ? 'bg-success' : 
                                                (($schedule['status'] == 'cancelled') ? 'bg-danger' : 
                                                (($schedule['status'] == 'completed') ? 'bg-info' : 'bg-warning')); 
                                        ?>">
                                        <?php echo ucfirst($schedule['status']); ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="text-right mt-4">
                            <div class="btn-group">
                                <?php if ($schedule['status'] == 'cancelled') { ?>
                                    <button class="btn btn-danger btn-sm rounded ml-2" data-toggle="modal" data-target="#modal-md<?php echo $schedule['schedule_id']; ?>">Delete</button>
                                <?php } elseif ($schedule['status'] == 'confirmed') { ?>
                                    <!-- Unconfirm Button -->
                                    <button class="btn btn-warning btn-sm rounded ml-2" data-toggle="modal" data-target="#modal-unconfirm<?php echo $schedule['schedule_id']; ?>">Unconfirm</button>

                                    <!-- Finish Button -->
                                    <button class="btn btn-primary btn-sm rounded ml-2" data-toggle="modal" data-target="#modal-finish<?php echo $schedule['schedule_id']; ?>">Finish</button>
                                <?php } elseif ($schedule['status'] == 'pending') { ?>
                                    <!-- Reschedule Button -->
                                    <a href="reschedule.php?schedule_id=<?php echo $schedule['schedule_id']; ?>" class="btn btn-warning btn-sm rounded"><strong>Reschedule</strong></a>

                                    <!-- Confirm Button -->
                                    <button class="btn btn-success btn-sm rounded ml-2" data-toggle="modal" data-target="#modal-confirm<?php echo $schedule['schedule_id']; ?>">Confirm</button>
                                <?php } ?>
                            </div>
                        </div>

                        <!-- Confirm Modal -->
                        <div class="modal fade" id="modal-confirm<?php echo $schedule['schedule_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel<?php echo $schedule['schedule_id']; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-md" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-success" id="confirmModalLabel<?php echo $schedule['schedule_id']; ?>"><b>Confirm Schedule</b></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to confirm the schedule for 
                                            <b><?php echo strtoupper($schedule['student_lname']) . ', ' . strtoupper($schedule['student_fname']); ?></b>?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <a href="confirm.schedule.php?schedule_id=<?php echo $schedule['schedule_id']; ?>&action=confirm" class="btn btn-success">Confirm</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Finish Appointment Modal -->
                        <div class="modal fade" id="modal-finish<?php echo $schedule['schedule_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="finishModalLabel<?php echo $schedule['schedule_id']; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-md" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-primary" id="finishModalLabel<?php echo $schedule['schedule_id']; ?>"><b>Complete Appointment</b></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to mark the appointment for 
                                            <b><?php echo strtoupper($schedule['student_lname']) . ', ' . strtoupper($schedule['student_fname']); ?></b> 
                                            as completed?
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <a href="confirm.schedule.php?schedule_id=<?php echo $schedule['schedule_id']; ?>&action=complete" class="btn btn-primary">Finish</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Unconfirm Modal -->
                        <div class="modal fade" id="modal-unconfirm<?php echo $schedule['schedule_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="unconfirmModalLabel<?php echo $schedule['schedule_id']; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-md" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-warning" id="unconfirmModalLabel<?php echo $schedule['schedule_id']; ?>"><b>Unconfirm Schedule</b></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to unconfirm the schedule for 
                                            <b><?php echo strtoupper($schedule['student_lname']) . ', ' . strtoupper($schedule['student_fname']); ?></b>?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <a href="confirm.schedule.php?schedule_id=<?php echo $schedule['schedule_id']; ?>&action=unconfirm" class="btn btn-warning">Unconfirm</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal for delete confirmation -->
                        <div class="modal fade" id="modal-md<?php echo $schedule['schedule_id']; ?>">
                            <div class="modal-dialog modal-md">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title text-danger"><b>Delete Schedule</b></h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete the schedule for 
                                            <b><?php 
                                                echo strtoupper($schedule['student_lname']) . ', ' . strtoupper($schedule['student_fname']); 
                                                if (isset($schedule['student_mname']) && !empty($schedule['student_mname'])) {
                                                    echo ' ' . strtoupper($schedule['student_mname']);
                                                }
                                            ?></b>?
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <a href="userData/ctrl.del.schedule.php?schedule_id=<?php echo $schedule['schedule_id']; ?>" class="btn btn-danger">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
                <style>
                    /* Apply hover effect to card headers */
                    .card-header:hover {
                        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
                    }

                    .hover-effect {
                        transition: transform 0.3s ease; /* Smooth transition */
                    }

                    .hover-effect:hover {
                        transform: scale(1.05); /* Scale up on hover */
                    }

                </style>
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
</body>

</html>

<?php
$stmt->close(); // Close the prepared statement
$conn->close(); // Close the database connection
?>
