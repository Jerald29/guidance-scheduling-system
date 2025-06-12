<?php
require '../../includes/session.php';

// Ensure the user is an administrator
if ($_SESSION['role'] != 'Administrator') {
    $_SESSION['error'] = "Unauthorized access.";
    header('location: ../../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student's Profile List | GCS Bacoor</title>
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
                            <h1 class="m-0">Student's Profile List</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">

                <!-- Default box -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"></h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="GET">
                            <div class="row justify-content-center">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" name="search" class="form-control" placeholder="Search student">
                                    </div>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Student's Image</th>
                                    <th>Student ID</th>
                                    <th>Student's Full Name</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($_GET['search'])) {
                                    $search = addslashes($_GET['search']);

                                    // Modified query with table aliases for tbl_students (stud) and tbl_stud_info (info)
                                    $student_info = mysqli_query($conn, "SELECT stud.*, 
                                                                                CONCAT(stud.student_lname, ', ', stud.student_fname, ' ', stud.student_mname) as fullname, 
                                                                                info.info_id 
                                                                        FROM tbl_students AS stud
                                                                        LEFT JOIN tbl_stud_info AS info ON stud.student_id = info.student_id
                                                                        WHERE (stud.student_fname LIKE '%$search%' 
                                                                                OR stud.student_mname LIKE '%$search%' 
                                                                                OR stud.student_lname LIKE '%$search%' 
                                                                                OR stud.stud_no LIKE '%$search%')
                                                                        ORDER BY stud.student_lname");

                                    while ($row = mysqli_fetch_array($student_info)) {
                                ?>
                                <tr>
                                    <td class="text-center">
                                        <?php
                                        if (!empty(base64_encode($row['img']))) {
                                            echo '<img src="data:image/jpeg;base64,' . base64_encode($row['img']) . '" class="img zoom " alt="User image" style="height: 100px; width: 100px">';
                                        } else {
                                            echo '<img src="../../docs/assets/img/user2.png" class="img zoom" alt="User image"  style="height: 100px; width: 100px">';
                                        } ?>
                                    </td>
                                    <td><?php echo $row['stud_no'] ?></td>
                                    <td><?php echo $row['fullname'] ?></td>
                                    <td><?php echo $row['email'] ?></td> 
                                    <td class="text-center">
                                        <?php if (!$row['info_id']): ?>
                                            <button class="btn btn-warning btn-sm rounded" data-toggle="modal" data-target="#profileIncompleteModal<?= htmlspecialchars($row['student_id']) ?>">View Profile</button>
                                            <!-- Modal for Incomplete Profile -->
                                            <div class="modal fade" id="profileIncompleteModal<?= htmlspecialchars($row['student_id']) ?>" tabindex="-1" role="dialog">
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
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <a href="view.student.form.php?student_id=<?= htmlspecialchars($row['student_id']) ?>" class="btn btn-info btn-sm m-1">View Profile</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer"></div>
                    <!-- /.card-footer-->
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
