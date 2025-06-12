<?php
require '../../includes/session.php';
require '../../includes/conn.php'; 

// Ensure the user is an Administrator / Guidance Advocate
if ($_SESSION['role'] != 'Administrator') {
    $_SESSION['error'] = "Unauthorized access.";
    header('Location: ../../login.php');
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
    <title>Student Evaluation | GCS Bacoor</title>
    <link rel="icon" href="../../docs/assets/img/gcs-bac.png">

    <?php include '../../includes/links.php'; ?>
    <style>
        /* Custom styles for table cells to prevent overflow */
        .table td {
            overflow: hidden; /* Prevent overflow */
            text-overflow: ellipsis; /* Show ellipsis for overflow text */
            white-space: nowrap; /* Prevent text wrapping */
        }
    </style>
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
                            <h1 class="m-0">Student Evaluation</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
                                <li class="breadcrumb-item active">Student Evaluation</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">List of Students</h3>
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
                                    <th>Student's Image</th>
                                    <th>Student's Full Name</th>
                                    <th>Email</th>
                                    <th>Username</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch evaluations from the database with student names, images, email, and username
                                $sql = "SELECT st.student_id, 
                                               CONCAT(st.student_lname, ', ', st.student_fname, ' ', st.student_mname) AS student_name,
                                               st.img, st.email, st.username,
                                               e.eval_id, si.info_id
                                        FROM tbl_students AS st
                                        LEFT JOIN tbl_evaluations AS e ON st.student_id = e.student_id
                                        LEFT JOIN tbl_stud_info si ON st.student_id = si.student_id
                                        ORDER BY st.student_lname, st.student_fname";

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
                                    <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <?php if (empty($row['eval_id'])): ?>
                                                <?php if (empty($row['info_id'])): ?>
                                                    <!-- Button with yellow color (btn-warning) for incomplete profile -->
                                                    <button class="btn btn-warning btn-sm rounded mr-2" data-toggle="modal" data-target="#profileModal<?= htmlspecialchars($row['student_id']) ?>">Add Evaluation</button>

                                                    <!-- Modal for Incomplete Profile -->
                                                    <div class="modal fade" id="profileModal<?= htmlspecialchars($row['student_id']) ?>" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog modal-md">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title text-danger"><b>Student's Profile Incomplete</b></h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>This student has not completed their profile.<br>
                                                                    Please have them complete it for a more accurate evaluation.</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <!-- Normal button (green) for adding evaluation if profile is complete -->
                                                    <a href="add.eval.php?student_id=<?= htmlspecialchars($row['student_id']) ?>" class="btn btn-success btn-sm rounded mr-2">Add Evaluation</a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <a href="view.eval.php?eval_id=<?= htmlspecialchars($row['eval_id']) ?>" class="btn btn-info btn-sm rounded mr-2">View Evaluation</a>
                                                <a href="edit.eval.php?eval_id=<?= htmlspecialchars($row['eval_id']) ?>" class="btn btn-warning btn-sm rounded mr-2">Edit</a>
                                                <button class="btn btn-danger btn-sm rounded" data-toggle="modal" data-target="#modal-md<?= htmlspecialchars($row['eval_id']) ?>">Delete</button>

                                                <!-- Modal for Delete Confirmation -->
                                                <div class="modal fade" id="modal-md<?= htmlspecialchars($row['eval_id']) ?>">
                                                    <div class="modal-dialog modal-md">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title text-danger"><b>Delete Evaluation</b></h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body" style="word-wrap: break-word;">
                                                                <p>Are you sure you want to delete the evaluation for:</p>
                                                                <p><b><?= strtoupper(htmlspecialchars($row['student_name'])) ?></b></p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                <a href="userData/ctrl.del.eval.php?eval_id=<?= htmlspecialchars($row['eval_id']) ?>" class="btn btn-danger">Delete</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End Modal -->
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>No evaluations found.</td></tr>";
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
