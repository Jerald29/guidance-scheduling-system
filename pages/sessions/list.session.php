<?php
require '../../includes/session.php'; // Start session management
require '../../includes/conn.php'; // Database connection

// Ensure the user is an Administrator / Guidance Advocate
if ($_SESSION['role'] != 'Administrator') {
    $_SESSION['error'] = "Unauthorized access.";
    header('location: ../../login.php');
    exit();
}

// Handle session messages
$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);

$sql = "SELECT s.student_id, 
               CONCAT(s.student_lname, ', ', s.student_fname, ' ', s.student_mname) AS student_name, 
               s.img, s.email, s.username, 
               COUNT(sf.session_id) AS session_count,
               si.info_id
        FROM tbl_students s
        LEFT JOIN tbl_session_forms sf ON s.student_id = sf.student_id
        LEFT JOIN tbl_stud_info si ON s.student_id = si.student_id
        GROUP BY s.student_id, s.student_lname, s.student_fname, s.student_mname, s.img, s.email, s.username, si.info_id
        ORDER BY s.student_lname, s.student_fname";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Notes | GCS Bacoor</title>
    <link rel="icon" href="../../docs/assets/img/gcs-bac.png">
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
                            <h1 class="m-0">Session Notes</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../dashboard/index.php">Home</a></li>
                                <li class="breadcrumb-item active">Session Notes</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">List of Students</h3>
                    </div>
                    <div class="card-body">
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
                                <?php if ($result->num_rows > 0): ?>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td class="text-center">
                                                <?php
                                                if (!empty($row['img'])) {
                                                    echo '<img src="data:image/jpeg;base64,' . base64_encode($row['img']) . '" class="img" alt="Student Image" style="height: 100px; width: 100px">';
                                                } else {
                                                    echo '<img src="../../docs/assets/img/user2.png" class="img" alt="Default User Image" style="height: 100px; width: 100px">';
                                                }
                                                ?>
                                            </td>
                                            <td><?= htmlspecialchars($row['student_name']) ?></td>
                                            <td><?= htmlspecialchars($row['email']) ?></td>
                                            <td><?= htmlspecialchars($row['username']) ?></td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <?php if ($row['session_count'] > 0): ?>
                                                        <a href="history.session.php?student_id=<?= htmlspecialchars($row['student_id']) ?>" class="btn btn-info btn-sm rounded mr-2">
                                                            View History (<?= $row['session_count'] ?>)
                                                        </a>
                                                    <?php endif; ?>

                                                    <?php if (!$row['info_id']): ?>
                                                        <!-- Warning button if profile is incomplete -->
                                                        <button class="btn btn-warning btn-sm rounded" data-toggle="modal" data-target="#profileModal<?= htmlspecialchars($row['student_id']) ?>">
                                                            Add Session
                                                        </button>

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
                                                                        <p>This student has not filled out their profile form yet.<br>
                                                                            Please remind them to complete their profile for better assistance.</p>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php else: ?>
                                                        <!-- Success button if profile is complete -->
                                                        <a href="add.session.php?student_id=<?= htmlspecialchars($row['student_id']) ?>" class="btn btn-success btn-sm">
                                                            Add Session
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>

                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No students found.</td> <!-- Adjusted colspan to match the new column -->
                                    </tr>
                                <?php endif; ?>
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
