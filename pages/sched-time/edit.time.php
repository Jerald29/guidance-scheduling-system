<?php
require '../../includes/session.php';
require '../../includes/conn.php';

// Ensure the user is an Administrator
if ($_SESSION['role'] != 'Administrator') {
    $_SESSION['error'] = "Unauthorized access.";
    header('Location: ../../login.php');
    exit();
}

// Check if 'id' parameter is passed in the URL
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Time Slot ID is missing!";
    header("Location: list.time.php");
    exit();
}

$time_id = $_GET['id'];

// Fetch time slot data from the database
$sql = "SELECT * FROM tbl_sched_time WHERE time_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $time_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = "Time Slot not found!";
    header("Location: list.time.php");
    exit();
}

$time_slot = $result->fetch_assoc();

// Check for duplicate error
$duplicate_error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
unset($_SESSION['error']); // Clear error session after displaying
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Time Slot | GCS Bacoor</title>
    <link rel="icon" href="../../docs/assets/img/gcs-bac.png">

    <?php include '../../includes/links.php'; ?>
</head>

<body class="hold-transition layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">

        <?php include '../../includes/navbar.php'; ?>
        <?php include '../../includes/sidebar.php'; ?>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Edit Time Slot</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="list.time.php">Time Slots</a></li>
                                <li class="breadcrumb-item active">Edit</li>
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
                        <h3 class="card-title">Edit Time Slot</h3>
                    </div>
                    <div class="card-body">

                        <!-- Edit Time Slot Form -->
                        <form action="userData/ctrl.edit.time.php" method="POST">
                            <input type="hidden" name="time_id" value="<?php echo $time_slot['time_id']; ?>">

                            <div class="form-group">
                                <label for="time_slot">Time Slot</label>
                                <input type="time" name="time_slot" id="time_slot" class="form-control" value="<?php echo htmlspecialchars($time_slot['time_slot']); ?>" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Update Time Slot</button>
                            <a href="list.time.php" class="btn btn-secondary">Cancel</a>
                        </form>
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

    <!-- Duplicate Time Slot Modal -->
    <?php if ($duplicate_error): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $('#duplicateTimeModal').modal('show');
        });
    </script>

    <div class="modal fade" id="duplicateTimeModal" tabindex="-1" role="dialog" aria-labelledby="duplicateTimeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="duplicateTimeModalLabel">Duplicate Time Slot</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo $duplicate_error; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</body>

</html>
