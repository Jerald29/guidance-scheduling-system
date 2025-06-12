<?php
require '../../includes/session.php'; // Include session handling
require '../../includes/conn.php'; // Include database connection

// Ensure the user is an Administrator / Guidance Advocate
if ($_SESSION['role'] != 'Administrator') {
    $_SESSION['error'] = "Unauthorized access.";
    header('Location: ../../login.php');
    exit();
}

// Check if 'id' parameter is passed in the URL (for the specific announcement to edit)
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Announcement ID is missing!";
    header("Location: list.announce.php");
    exit();
}

$announce_id = $_GET['id'];

// Fetch announcement data from the database
$sql = "SELECT * FROM tbl_announce WHERE announce_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $announce_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = "Announcement not found!";
    header("Location: list.announce.php");
    exit();
}

$announcement = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Announcement | GCS Bacoor</title>
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
                            <h1 class="m-0">Edit Announcement</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="list.announce.php">Announcements</a></li>
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
                        <h3 class="card-title">Edit Announcement</h3>
                    </div>
                    <div class="card-body">

                        <!-- Edit Announcement Form -->
                        <form action="userData/ctrl.edit.announce.php" method="POST">
                            <input type="hidden" name="announce_id" value="<?php echo $announcement['announce_id']; ?>">

                            <div class="form-group">
                                <label for="announce_content">Announcement Content</label>
                                <textarea name="announce_content" id="announce_content" class="form-control" rows="5" required><?php echo htmlspecialchars($announcement['announce_content']); ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Update Announcement</button>
                            <a href="list.announce.php" class="btn btn-secondary">Cancel</a>
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
</body>

</html>
