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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Completed Schedule List | GCS Bacoor</title>
    <?php include '../../includes/links.php'; ?>
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
                            <h1 class="m-0">Completed Schedule List</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../dashboard/index.php">Home</a></li>
                                <li class="breadcrumb-item active">Schedule List</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Schedule List</h3>
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

                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Student Name</th>
                                    <th>Appointment Date</th>
                                    <th>Appointment Time</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch completed schedules from the database
                                $sql = "SELECT s.schedule_id, st.student_fname, st.student_lname, st.student_mname, st.img, st.email, 
                                        s.appointment_date, s.appointment_time, s.status
                                        FROM tbl_schedules as s
                                        LEFT JOIN tbl_students as st ON s.student_id = st.student_id 
                                        WHERE s.status = 'completed'
                                        ORDER BY s.appointment_date ASC, s.appointment_time ASC";
                                
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
                                    <td><?php echo $row['student_lname'] . ', ' . $row['student_fname'] . ' ' . $row['student_mname']; ?></td>
                                    <td><?php echo date("F j, Y", strtotime($row['appointment_date'])); ?></td>
                                    <td><?php echo date("g:i A", strtotime($row['appointment_time'])); ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td>
                                        <span class="badge bg-info">Completed</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <!-- View Details Button -->
                                            <a href="view.schedule.php?schedule_id=<?php echo $row['schedule_id']; ?>" class="btn btn-info btn-sm rounded">View</a>

                                            <!-- Delete Button with Modal Trigger -->
                                            <button class="btn btn-danger btn-sm rounded ml-2" data-toggle="modal" data-target="#deleteModal<?php echo $row['schedule_id']; ?>">Delete</button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal<?php echo $row['schedule_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-md" role="document">
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
                                    echo "<tr><td colspan='7' class='text-center'>No completed schedules found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
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
$conn->close(); // Close the database connection
?>
