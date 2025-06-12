<?php
require '../../includes/session.php';

// Check if the user role is either 'Administrator' or 'Super Administrator'
if ($_SESSION['role'] != 'Administrator' && $_SESSION['role'] != 'Super Administrator') {
    $_SESSION['error'] = "Unauthorized access.";
    header('Location: ../../login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Student | GCS Bacoor</title>
    <link rel="icon" href="../../docs/assets/img/gcs-bac.png">
    <?php include '../../includes/links.php'; ?>

</head>

<body class="hold-transition layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">

        <?php include '../../includes/navbar.php' ?>

        <?php include '../../includes/sidebar.php' ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Add Student</h1>
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
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="card">
                                <form action="userData/ctrl.add.student.php" enctype="multipart/form-data" method="POST">
                                    <div class="card-header">
                                        <h3 class="card-title">Student Account</h3>
                                        <div class="card-tools">
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-4">
                                            <div class="custom-file">
                                                <div class="text-center">
                                                    <img class="img-fluid img-circle" src="../../docs/assets/img/user2.png"
                                                        alt="User profile picture" style="width: 145px; height: 145px; margin-bottom: 10px;">
                                                </div>
                                                <div class="row">
                                                    <div class="form-group mr-auto ml-auto col-md-6">
                                                        <div class="input-group">
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input" name="image" id="customFile">
                                                                <label class="custom-file-label" for="customFile">Choose file</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-4 mt-5">
                                            <div class="input-group col-sm-4 mb-2">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" class="form-control" name="student_fname"
                                                    placeholder="First name">
                                            </div>

                                            <div class="input-group col-sm-4 mb-2">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" class="form-control" name="student_mname"
                                                    placeholder="Middle name">
                                            </div>

                                            <div class="input-group col-sm-4 mb-2">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" class="form-control" name="student_lname"
                                                    placeholder="Last name">
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <div class="input-group col-sm-6 mb-2">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                                </div>
                                                <input type="text" class="form-control" name="lrn"
                                                    placeholder="LRN">
                                            </div>

                                            <div class="input-group col-sm-6 mb-2">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                                </div>
                                                <input type="text" class="form-control" name="stud_no"
                                                    placeholder="Student Number">
                                            </div>
                                        </div>
                                        <div class="row mb-4">

                                            <div class="input-group col-sm-6 mb-2">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                </div>
                                                <input type="email" class="form-control" name="email"
                                                    placeholder="Email Address" required>
                                            </div>

                                            <div class="input-group col-sm-6 mb-2">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" class="form-control" name="username"
                                                    placeholder="Username" required>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="input-group col-sm-6 mb-2">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                                </div>
                                                <input type="password" class="form-control" name="password"
                                                    placeholder="Password" required>
                                            </div>

                                            <div class="input-group col-sm-6 mb-2">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                                </div>
                                                <input type="password" class="form-control" name="password2"
                                                    placeholder="Confirm Password" required>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary btn-sm rounded float-right ml-2"
                                            name="submit">Submit
                                            </button>
                                    </div>
                                </form>
                                <!-- /.card-footer-->
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
    <!-- Trigger modals for errors -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php if (isset($_SESSION['email_error'])): ?>
                $('#emailErrorModal').modal('show');
            <?php endif; ?>
            <?php if (isset($_SESSION['username_error'])): ?>
                $('#usernameErrorModal').modal('show');
            <?php endif; ?>
            <?php if (isset($_SESSION['lrn_error'])): ?>
                $('#lrnErrorModal').modal('show');
            <?php endif; ?>
            <?php if (isset($_SESSION['stud_no_error'])): ?>
                $('#studNoErrorModal').modal('show');
            <?php endif; ?>
        });
    </script>

    <!-- Modal for Email Error -->
    <?php if (isset($_SESSION['email_error'])): ?>
    <div class="modal fade" id="emailErrorModal" tabindex="-1" role="dialog" aria-labelledby="emailErrorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="emailErrorModalLabel">Email Error</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?= $_SESSION['email_error']; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <?php unset($_SESSION['email_error']); ?>
    <?php endif; ?>

    <!-- Modal for Username Error -->
    <?php if (isset($_SESSION['username_error'])): ?>
    <div class="modal fade" id="usernameErrorModal" tabindex="-1" role="dialog" aria-labelledby="usernameErrorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="usernameErrorModalLabel">Username Error</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?= $_SESSION['username_error']; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <?php unset($_SESSION['username_error']); ?>
    <?php endif; ?>

    <!-- Modal for LRN Error -->
    <?php if (isset($_SESSION['lrn_error'])): ?>
    <div class="modal fade" id="lrnErrorModal" tabindex="-1" role="dialog" aria-labelledby="lrnErrorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="lrnErrorModalLabel">LRN Error</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?= $_SESSION['lrn_error']; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <?php unset($_SESSION['lrn_error']); ?>
    <?php endif; ?>

    <!-- Modal for Student Number Error -->
    <?php if (isset($_SESSION['stud_no_error'])): ?>
    <div class="modal fade" id="studNoErrorModal" tabindex="-1" role="dialog" aria-labelledby="studNoErrorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="studNoErrorModalLabel">Student Number Error</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?= $_SESSION['stud_no_error']; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <?php unset($_SESSION['stud_no_error']); ?>
    <?php endif; ?>

</body>

</html>
