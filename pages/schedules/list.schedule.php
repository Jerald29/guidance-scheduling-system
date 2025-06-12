<?php
require '../../includes/session.php'; // Include session handling
require '../../includes/conn.php'; // Include database connection

// Ensure the user is an administrator
if ($_SESSION['role'] != 'Administrator') {
    $_SESSION['error'] = "Unauthorized access.";
    header('location: ../../login.php');
    exit();
}

// Check for success or error messages in the session
$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
$error = isset($_SESSION['error']) ? $_SESSION['error'] : null;

// Clear the session messages after use
unset($_SESSION['success']);
unset($_SESSION['error']);

// Fetch the total counts for schedules, confirmed, pending, and cancelled
$sql_total = "SELECT 
    COUNT(*) AS total_schedules,
    SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) AS total_confirmed,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS total_pending,
    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) AS total_cancelled,
    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) AS total_completed
    FROM tbl_schedules";
$result_total = $conn->query($sql_total);
$totals = $result_total->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Schedule List | GCS Bacoor</title>
    <link rel="icon" href="../../docs/assets/img/gcs-bac.png">

    <?php include '../../includes/links.php'; ?>
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
                            <h1 class="m-0">Schedule List</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
                                <li class="breadcrumb-item active">Schedule List</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="card">
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

                        <!-- Display total schedules, confirmed, pending, and cancelled in a centered, evenly spaced row -->
                        <div class="d-flex flex-wrap justify-content-center align-items-center mb-3">
                            <span class="px-4 py-2 mx-2 my-1" style="background-color: #007bff; color: white; border-radius: 8px; font-size: 15px;">
                                <b>Total Scheduled:</b> <?php echo $totals['total_schedules']; ?>
                            </span>
                            <span class="px-4 py-2 mx-2 my-1" style="background-color: #28a745; color: white; border-radius: 8px; font-size: 15px;">
                                <b>Total Confirmed:</b> <?php echo $totals['total_confirmed']; ?>
                            </span>
                            <span class="px-4 py-2 mx-2 my-1" style="background-color: #17a2b8; color: white; border-radius: 8px; font-size: 15px;">
                                <b>Total Completed:</b> <?php echo $totals['total_completed']; ?>
                            </span>
                            <span class="px-4 py-2 mx-2 my-1" style="background-color: #ffc107; color: black; border-radius: 8px; font-size: 15px;">
                                <b>Total Pending:</b> <?php echo $totals['total_pending']; ?>
                            </span>
                            <span class="px-4 py-2 mx-2 my-1" style="background-color: #dc3545; color: white; border-radius: 8px; font-size: 15px;">
                                <b>Total Cancelled:</b> <?php echo $totals['total_cancelled']; ?>
                            </span>
                        </div>

                        <!-- Hover Effect -->
                        <style>
                            .d-flex span {
                                display: inline-block;
                                transition: transform 0.2s ease-in-out;
                            }

                            .d-flex span:hover {
                                transform: translateY(-3px);
                            }
                        </style>

                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Student's Image</th>
                                    <th>Student's Full Name</th>
                                    <th>Requested Date</th>
                                    <th>Requested Time</th>
                                    <th>Email</th> 
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch schedules from the database with student names, images, and emails
                                $sql = "SELECT 
                                s.schedule_id, 
                                st.student_fname, 
                                st.student_lname, 
                                st.student_mname, 
                                st.img, 
                                st.email, 
                                s.appointment_date, 
                                s.appointment_time, 
                                s.status
                            FROM tbl_schedules as s
                            LEFT JOIN tbl_students as st ON s.student_id = st.student_id
                            ORDER BY 
                                CASE 
                                    WHEN s.appointment_date >= CURDATE() THEN 1 
                                    ELSE 2 
                                END ASC, 
                                s.appointment_date ASC, 
                                s.appointment_time ASC";                    
                                
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td class="text-center">
                                        <?php
                                        // Display student image or default image
                                        if (!empty($row['img'])) {
                                            echo '<img src="data:image/jpeg;base64,' . base64_encode($row['img']) . '" class="img" alt="Student Image" style="height: 100px; width: 100px">';
                                        } else {
                                            echo '<img src="../../docs/assets/img/user2.png" class="img" alt="Default User Image" style="height: 100px; width: 100px">';
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo $row['student_lname'] . ', ' . $row['student_fname']. ' ' . $row['student_mname']; ?></td>
                                    <td><?php echo date("F j, Y", strtotime($row['appointment_date'])); ?></td>
                                    <td><?php echo date("g:i A", strtotime($row['appointment_time'])); ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td>
                                        <span class="badge 
                                            <?php 
                                                echo ($row['status'] == 'confirmed') ? 'bg-success' : 
                                                    (($row['status'] == 'cancelled') ? 'bg-danger' : 
                                                    (($row['status'] == 'completed') ? 'bg-info' : 'bg-warning')); 
                                            ?>">
                                            <?php echo ucfirst($row['status']); ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <!-- View Details Button -->
                                            <a href="view.schedule.php?schedule_id=<?php echo $row['schedule_id']; ?>" class="btn btn-info btn-sm rounded">View</a>

                                            <?php if ($row['status'] != 'cancelled') { ?>
                                                <?php if ($row['status'] == 'confirmed') { ?>
                                                    <!-- Confirmed: Show Finish Button -->
                                                    <button class="btn btn-primary btn-sm rounded ml-2" data-toggle="modal" data-target="#modal-finish<?php echo $row['schedule_id']; ?>">Finish</button>
                                                    <!-- Confirmed: Show Unconfirm Button -->
                                                    <button class="btn btn-warning btn-sm rounded ml-2" data-toggle="modal" data-target="#modal-unconfirm<?php echo $row['schedule_id']; ?>">Unconfirm</button>
                                                <?php } elseif ($row['status'] == 'pending') { ?>
                                                    <!-- Pending: Show Confirm & Reschedule Buttons -->
                                                    <button class="btn btn-success btn-sm rounded ml-2" data-toggle="modal" data-target="#modal-confirm<?php echo $row['schedule_id']; ?>">Confirm</button>

                                                    <a href="reschedule.php?schedule_id=<?php echo $row['schedule_id']; ?>" class="btn btn-warning btn-sm rounded ml-2">Reschedule</a>
                                                <?php } ?>
                                            <?php } ?>

                                            <!-- Delete Button -->
                                            <button class="btn btn-danger btn-sm rounded ml-2" data-toggle="modal" data-target="#modal-md<?php echo $row['schedule_id']; ?>">Delete</button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Confirm Modal -->
                                <div class="modal fade" id="modal-confirm<?php echo $row['schedule_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel<?php echo $row['schedule_id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-md" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-success" id="confirmModalLabel<?php echo $row['schedule_id']; ?>"><b>Confirm Schedule</b></h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to confirm the schedule for 
                                                    <b><?php echo strtoupper($row['student_lname']) . ', ' . strtoupper($row['student_fname']); ?></b>?
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <a href="confirm.schedule.php?schedule_id=<?php echo $row['schedule_id']; ?>&action=confirm" class="btn btn-success">Confirm</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Finish Modal -->
                                <div class="modal fade" id="modal-finish<?php echo $row['schedule_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="finishModalLabel<?php echo $row['schedule_id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-md" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-primary" id="finishModalLabel<?php echo $row['schedule_id']; ?>"><b>Complete Appointment</b></h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Mark the appointment for 
                                                    <b><?php echo strtoupper($row['student_lname']) . ', ' . strtoupper($row['student_fname']); ?></b> 
                                                    as completed?
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <a href="confirm.schedule.php?schedule_id=<?php echo $row['schedule_id']; ?>&action=complete" class="btn btn-primary">Finish</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Unconfirm Modal -->
                                <div class="modal fade" id="modal-unconfirm<?php echo $row['schedule_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="unconfirmModalLabel<?php echo $row['schedule_id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-md" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-warning" id="unconfirmModalLabel<?php echo $row['schedule_id']; ?>"><b>Unconfirm Schedule</b></h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to unconfirm the schedule for 
                                                    <b><?php echo strtoupper($row['student_lname']) . ', ' . strtoupper($row['student_fname']); ?></b>?
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <a href="confirm.schedule.php?schedule_id=<?php echo $row['schedule_id']; ?>&action=unconfirm" class="btn btn-warning">Unconfirm</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal for delete confirmation -->
                                <div class="modal fade" id="modal-md<?php echo $row['schedule_id']; ?>">
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
                                                        echo strtoupper($row['student_lname']) . ', ' . strtoupper($row['student_fname']); 
                                                        if (isset($row['student_mname']) && !empty($row['student_mname'])) {
                                                            echo ' ' . strtoupper($row['student_mname']);
                                                        }
                                                    ?></b>?
                                                </p>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <a href="userData/ctrl.del.schedule.php?schedule_id=<?php echo $row['schedule_id']; ?>" class="btn btn-danger">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal -->

                                <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='7' class='text-center'>No schedules found.</td></tr>";
                                }
                                ?>
                            </tbody>

                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
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
$conn->close(); // Close the database connection
?>
