<?php
require '../../includes/session.php';

// Ensure the user is a Super Administrator
if ($_SESSION['role'] != 'Super Administrator') {
    $_SESSION['error'] = "Unauthorized access.";
    header('Location: ../../login.php');
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the course name from the POST request
    $course_name = isset($_POST['course_name']) ? trim($_POST['course_name']) : '';

    // Validate the course name
    if (empty($course_name)) {
        $_SESSION['errors'][] = "Course name is required.";
    } else {
        // Prepare and execute the insert statement
        $stmt = $conn->prepare("INSERT INTO tbl_courses (course_name) VALUES (?)");
        $stmt->bind_param("s", $course_name);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Program added successfully.";
            header("Location: list.courses.php"); // Redirect to course list
            exit();
        } else {
            $_SESSION['errors'][] = "Failed to add course. Please try again.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Program | GCS Bacoor</title>
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
                            <h1 class="m-0">Add Program</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../dashboard/index.php">Home</a></li>
                                <li class="breadcrumb-item active">Add Program</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Add New Program</h3>
                        </div>

                        <div class="card-body">
                            <!-- Display success or error messages -->
                            <?php
                            if (!empty($_SESSION['errors'])) {
                                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                                foreach ($_SESSION['errors'] as $error) {
                                    echo $error . "<br>";
                                }
                                echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button></div>';
                                unset($_SESSION['errors']);
                            } elseif (!empty($_SESSION['success'])) {
                                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>' . $_SESSION['success'] . '</strong>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>';
                                unset($_SESSION['success']);
                            }
                            ?>

                            <!-- Form for adding a new course -->
                            <form action="userData/ctrl.add.courses.php" method="POST">
                                <div class="form-group">
                                    <label for="course_name">Program Name</label>
                                    <input type="text" name="course_name" class="form-control" required placeholder="Enter Program Name">
                                </div>
                                <div class="form-group">
                                    <label for="course_abv">Course Abbreviation</label>
                                    <input type="text" name="course_abv" class="form-control" required placeholder="Enter Course Abbreviation">
                                </div>
                                <div class="form-group mt-5">
                                    <button type="submit" class="btn bg-primary" name="submit">Add Program</button>
                                </div>
                            </form>
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

<?php
mysqli_close($conn); // Close the database connection
?>
