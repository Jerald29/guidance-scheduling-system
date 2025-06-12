<?php
require '../../includes/session.php'; 

if (!isset($_SESSION['id']) || !isset($_SESSION['role'])) {
    header("Location: ../../login.php");
    exit();
}

// Fetch user info based on role
if ($_SESSION['role'] === "Student") {
    $user_info = mysqli_query($conn, "SELECT * FROM tbl_students WHERE student_id = '{$_SESSION['id']}'");
} elseif ($_SESSION['role'] === "Administrator") {
    $user_info = mysqli_query($conn, "SELECT * FROM tbl_admins WHERE admin_id = '{$_SESSION['id']}'");
} elseif ($_SESSION['role'] === "Super Administrator") {
    $user_info = mysqli_query($conn, "SELECT * FROM tbl_master_key WHERE mk_id = '{$_SESSION['id']}'");
}
$row = mysqli_fetch_array($user_info);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Account | GCS Bacoor</title>
    <?php include '../../includes/links.php'; ?>
</head>
<body class="hold-transition layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <?php include '../../includes/navbar.php'; ?>
        <?php include '../../includes/sidebar.php'; ?>

        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h1 class="m-0">Edit Account</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../dashboard/index.php">Home</a></li>
                                <li class="breadcrumb-item active">Edit Account</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Section -->
            <section class="content">
                <div class="container-fluid h-100 d-flex align-items-center justify-content-center">
                    <div class="row w-100">
                        <div class="col-md-8 mx-auto">
                            <div class="card">

                                <!-- Display alerts if any -->
                                <?php if (isset($_SESSION['update_success'])): ?>
                                    <div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        Account updated successfully!
                                    </div>
                                    <?php unset($_SESSION['update_success']); ?>
                                <?php elseif (isset($_SESSION['password_unmatch'])): ?>
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h5><i class="icon fas fa-ban"></i> Error!</h5>
                                        Passwords do not match. Please try again.
                                    </div>
                                    <?php unset($_SESSION['password_unmatch']); ?>
                                <?php elseif (isset($_SESSION['update_error'])): ?>
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h5><i class="icon fas fa-ban"></i> Error!</h5>
                                        Error: <?php echo $_SESSION['update_error']; ?>
                                    </div>
                                    <?php unset($_SESSION['update_error']); ?>
                                <?php endif; ?>

                                <form method="POST" action="userData/ctrl.edit.account.php">
                                    <div class="card-header text-center">
                                        <h3 class="card-title">Account Information</h3>
                                    </div>
                                    <div class="card-body">
                                        <?php if ($_SESSION['role'] !== "Super Administrator"): ?>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>Username</label>
                                                        <input class="form-control" type="text" name="username" 
                                                               value="<?php echo htmlspecialchars($row['username']); ?>" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <input class="form-control" type="email" name="email" 
                                                               value="<?php echo htmlspecialchars($row['email']); ?>" required>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>New Password</label>
                                                    <input class="form-control" type="password" name="password" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Confirm Password</label>
                                                    <input class="form-control" type="password" name="password2" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white text-center">
                                        <button type="submit" class="btn btn-primary" name="submit">
                                            Update Account
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <?php include '../../includes/footer.php'; ?>
    </div>

    <?php include '../../includes/script.php'; ?>
</body>
</html>

