<?php
require '../../includes/session.php'; // Include session handling
require '../../includes/conn.php'; // Include database connection

// Ensure the user is a student
if ($_SESSION['role'] != 'Student') {
    $_SESSION['error'] = "Unauthorized access.";
    header('location: ../../login.php');
    exit();
}

// Get student ID from the session
$student_id = $_SESSION['id'];

// Fetch the scheduled appointments for the logged-in student, ordered by date and time
$schedule_info = $conn->prepare("SELECT *, reschedule FROM tbl_schedules WHERE student_id = ? ORDER BY appointment_date ASC, appointment_time ASC");
$schedule_info->bind_param("i", $student_id);
$schedule_info->execute();
$result = $schedule_info->get_result();

// Fetch totals for scheduled, confirmed, and pending appointments
$total_schedules_query = $conn->prepare("SELECT COUNT(*) as total FROM tbl_schedules WHERE student_id = ?");
$total_schedules_query->bind_param("i", $student_id);
$total_schedules_query->execute();
$total_schedules_result = $total_schedules_query->get_result();
$total_schedules = $total_schedules_result->fetch_assoc()['total'];

$total_confirmed_query = $conn->prepare("SELECT COUNT(*) as confirmed FROM tbl_schedules WHERE student_id = ? AND status = 'confirmed'");
$total_confirmed_query->bind_param("i", $student_id);
$total_confirmed_query->execute();
$total_confirmed_result = $total_confirmed_query->get_result();
$total_confirmed = $total_confirmed_result->fetch_assoc()['confirmed'];

$total_completed_query = $conn->prepare("SELECT COUNT(*) as completed FROM tbl_schedules WHERE student_id = ? AND status = 'completed'");
$total_completed_query->bind_param("i", $student_id);
$total_completed_query->execute();
$total_completed_result = $total_completed_query->get_result();
$total_completed = $total_completed_result->fetch_assoc()['completed'];

$total_pending_query = $conn->prepare("SELECT COUNT(*) as pending FROM tbl_schedules WHERE student_id = ? AND status = 'pending'");
$total_pending_query->bind_param("i", $student_id);
$total_pending_query->execute();
$total_pending_result = $total_pending_query->get_result();
$total_pending = $total_pending_result->fetch_assoc()['pending'];

$total_cancelled_query = $conn->prepare("SELECT COUNT(*) as cancelled FROM tbl_schedules WHERE student_id = ? AND status = 'cancelled'");
$total_cancelled_query->bind_param("i", $student_id);
$total_cancelled_query->execute();
$total_cancelled_result = $total_cancelled_query->get_result();
$total_cancelled = $total_cancelled_result->fetch_assoc()['cancelled'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Scheduled Appointments | GCS Bacoor</title>
    <link rel="icon" href="../../docs/assets/img/gcs-bac.png">

    <?php include '../../includes/links.php'; // Bootstrap and other CSS files ?>
    <!-- Include DataTables CSS -->
    <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
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
                            <h1 class="m-0">My Scheduled Appointments</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
                                <li class="breadcrumb-item active">My Appointments</li>
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

                        <!-- Display success or error messages -->
                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success!</strong> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php elseif (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php endif; ?>

                        <!-- Display total schedules, confirmed, pending, and cancelled -->
                        <div class="d-flex flex-wrap justify-content-center align-items-center gap-2 mb-3">
                            <span class="px-4 py-2 mx-2 my-1" style="background-color: #007bff; color: white; border-radius: 8px; font-size: 15px;">
                                <b>Total Scheduled:</b> <?php echo $total_schedules; ?>
                            </span>
                            <span class="px-4 py-2 mx-2 my-1" style="background-color: #28a745; color: white; border-radius: 8px; font-size: 15px;">
                                <b>Total Confirmed:</b> <?php echo $total_confirmed; ?>
                            </span>
                            <span class="px-4 py-2 mx-2 my-1" style="background-color: #17a2b8; color: white; border-radius: 8px; font-size: 15px;">
                                <b>Total Completed:</b> <?php echo $total_completed; ?>
                            </span>
                            <span class="px-4 py-2 mx-2 my-1" style="background-color: #ffc107; color: black; border-radius: 8px; font-size: 15px;">
                                <b>Total Pending:</b> <?php echo $total_pending; ?>
                            </span>
                            <span class="px-4 py-2 mx-2 my-1" style="background-color: #dc3545; color: white; border-radius: 8px; font-size: 15px;">
                                <b>Total Cancelled:</b> <?php echo $total_cancelled; ?>
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

                        <table id="appointmentsTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Requested Date</th>
                                    <th>Requested Time</th>
                                    <th>Created At</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result->num_rows > 0): ?>
                                    <?php while ($schedule = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars(date('F j, Y', strtotime($schedule['appointment_date']))); ?></td>
                                            <td><?php echo htmlspecialchars(date('g:i A', strtotime($schedule['appointment_time']))); ?></td>
                                            <td>
                                                <?php
                                                // Format the created_at field
                                                echo htmlspecialchars(date('F j, Y, g:i A', strtotime($schedule['created_at']))); 
                                                ?>
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    <?php 
                                                        echo ($schedule['status'] == 'confirmed') ? 'bg-success' : 
                                                            (($schedule['status'] == 'cancelled') ? 'bg-danger' : 
                                                            (($schedule['status'] == 'completed') ? 'bg-info' : 'bg-warning')); 
                                                    ?>">
                                                    <?php echo ucfirst($schedule['status']); ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="view.my.schedule.php?schedule_id=<?= $schedule['schedule_id']; ?>" class="btn btn-info btn-sm rounded">View</a>

                                                    <?php if ($schedule['status'] == 'pending' && $schedule['reschedule'] != 1): ?>
                                                        <a href="edit.schedule.php?schedule_id=<?= $schedule['schedule_id']; ?>" class="btn btn-warning btn-sm rounded ml-2">Edit</a>
                                                    <?php endif; ?>

                                                    <?php if ($schedule['status'] == 'pending'): ?>
                                                        <button class="btn btn-danger btn-sm rounded ml-2" data-toggle="modal" data-target="#modal-delete-<?= $schedule['schedule_id']; ?>">Delete</button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Modal for delete confirmation -->
                                        <div class="modal fade" id="modal-delete-<?php echo $schedule['schedule_id']; ?>">
                                            <div class="modal-dialog modal-md">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title text-danger"><b>Delete Schedule</b></h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete the schedule for <b><?php echo htmlspecialchars(date('F j, Y', strtotime($schedule['appointment_date']))); ?></b> at <b><?php echo htmlspecialchars(date('g:i A', strtotime($schedule['appointment_time']))); ?></b>?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        <a href="userData/ctrl.del.list.my.schedule.php?schedule_id=<?php echo $schedule['schedule_id']; ?>" class="btn btn-danger">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End of modal -->
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No scheduled appointments found.</td>
                                    </tr>
                                <?php endif; ?>
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
    <!-- Include DataTables JS -->
    <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#appointmentsTable').DataTable({
                "responsive": true,
                "lengthChange": true, 
                "autoWidth": false,
                "searching": true, 
                "order": [], 
                "columnDefs": [
                    { "orderable": false, "targets": [0, 1, 2, 3, 4] } 
                ]
            });
        });
    </script>
</body>

</html>

<?php
$conn->close(); // Close the database connection
?>
