<?php
require '../../includes/session.php';

// Check user role
if ($_SESSION['role'] != 'Super Administrator') {
    $_SESSION['error'] = "Unauthorized access.";
    header('Location: ../../login.php');
    exit();
}

$admin_id = $_GET['admin_id'];

// Check for alert messages from session
$alert_message = '';
$alert_class = '';

// Unified alert message
if (isset($_SESSION['alert_message'])) {
    $alert_message = $_SESSION['alert_message'];
    $alert_class = isset($_SESSION['alert_class']) ? $_SESSION['alert_class'] : 'alert-success'; // Default to success if not specified
    unset($_SESSION['alert_message']);
    unset($_SESSION['alert_class']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Administrator | GCS Bacoor</title>
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
                            <h1 class="m-0">Edit Administrator</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#"></a></li>
                                <li class="breadcrumb-item active"></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="card">
                                <form action="userData/ctrl.edit.administrator.php?admin_id=<?php echo $admin_id; ?>" enctype="multipart/form-data" method="POST">
                                    <div class="card-header">
                                        <h3 class="card-title">Administrator Info</h3>
                                    </div>
                                    <?php
                                    $admin_info = mysqli_query($conn, "SELECT * FROM tbl_admins WHERE admin_id = '$admin_id'");
                                    while ($row = mysqli_fetch_array($admin_info)) {
                                    ?>
                                    <div class="card-body">
                                        <?php if ($alert_message != '') { ?>
                                            <div class="alert <?php echo $alert_class; ?> alert-dismissible fade show" role="alert">
                                                <?php echo $alert_message; ?>
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        <?php } ?>
                                        <div class="card-body">
                                        <div class="form-group mb-4">
                                            <div class="custom-file">
                                            <div class="text-center">
                                                <?php if (!empty($row['img'])): ?>
                                                    <img class="img-fluid img-bordered img-circle p-1"
                                                        src="data:image/jpeg;base64, <?php echo base64_encode($row['img']); ?>"
                                                        alt="User profile picture" style="width: 145px; height: 145px; margin-bottom: 10px;">
                                                <?php else: ?>
                                                    <img class="img-fluid img-bordered img-circle p-1"
                                                        src="../../docs/assets/img/user2.png"
                                                        alt="User profile picture" style="width: 145px; height: 145px; margin-bottom: 10px;">
                                                <?php endif; ?>
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
                                                    <span class="input-group-text"><i class="fas fa-keyboard"></i></span>
                                                </div>
                                                <input type="text" class="form-control" name="admin_fname" value="<?php echo $row['admin_fname']; ?>" placeholder="First name" required>
                                            </div>

                                            <div class="input-group col-sm-4 mb-2">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-keyboard"></i></span>
                                                </div>
                                                <input type="text" class="form-control" name="admin_mname" value="<?php echo $row['admin_mname']; ?>" placeholder="Middle name">
                                            </div>

                                            <div class="input-group col-sm-4 mb-2">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-keyboard"></i></span>
                                                </div>
                                                <input type="text" class="form-control" name="admin_lname" value="<?php echo $row['admin_lname']; ?>" placeholder="Last name" required>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="input-group col-sm-6 mb-2">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                </div>
                                                <input type="email" class="form-control" name="email" value="<?php echo $row['email']; ?>" placeholder="Email Address" required>
                                            </div>

                                            <div class="input-group col-sm-6 mb-2">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" class="form-control" name="username" value="<?php echo $row['username']; ?>" placeholder="Username" required>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="input-group col-sm-6 mb-2">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                                </div>
                                                <input type="password" class="form-control" name="password" placeholder="Password" required>
                                            </div>

                                            <div class="input-group col-sm-6 mb-2">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                                </div>
                                                <input type="password" class="form-control" name="password2" placeholder="Confirm Password" required>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="card-footer bg-white text-center">
                                        <button type="submit" class="btn btn-primary" name="submit">Update Account</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <?php include '../../includes/footer.php'; ?>
        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>

    <?php include '../../includes/script.php'; ?>
</body>

</html>
