<?php
require '../../includes/session.php';

// Ensure the user is an Administrator / Guidance Advocate
if ($_SESSION['role'] != 'Administrator') {
    $_SESSION['error'] = "Unauthorized access.";
    header('Location: ../../login.php');
    exit();
}

// Fetch student ID from the GET request
$student_id = isset($_GET['student_id']) ? $_GET['student_id'] : null;

// Check if the student ID is present
if (!$student_id) {
    $_SESSION['errors'][] = "No student ID provided.";
    header("Location: ../evaluation/list.eval.php");
    exit();
}

// Fetch student details along with program and level names in one query
$query = "
    SELECT s.*, si.*, cou.course_name, cou.course_abv, lev.level_name, lev.level_abv
    FROM tbl_students s
    JOIN tbl_stud_info si ON s.student_id = si.student_id
    LEFT JOIN tbl_courses cou ON si.course_id = cou.course_id
    LEFT JOIN tbl_levels lev ON si.level_id = lev.level_id
    WHERE s.student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

// Clear previous session errors related to student ID
if (isset($_SESSION['errors'])) {
    foreach ($_SESSION['errors'] as $key => $error) {
        if ($error == "No student ID provided.") {
            unset($_SESSION['errors'][$key]);
        }
    }
}

$student = $result->fetch_assoc();

// Prepare student data
$full_name = $student['student_lname'] . ', ' . $student['student_fname'] . ($student['student_mname'] ? ' ' . $student['student_mname'] : '');
$course_name = htmlspecialchars($student['course_name']);
$level_name = htmlspecialchars($student['level_name']);
$course_abv = htmlspecialchars($student['course_abv']);
$level_abv = htmlspecialchars($student['level_abv']);

// Check if the student has an image
$student_image = !empty($student['img']) ? base64_encode($student['img']) : null;

$stmt->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Student Evaluation | GCS Bacoor</title>
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
                            <h1 class="m-0">Add Student Evaluation</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../dashboard/index.php">Home</a></li>
                                <li class="breadcrumb-item active">Add Evaluation</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header bg-olive text-white">
                            <h3 class="card-title">Add Evaluation for Student: <b><?php echo $full_name; ?></b></h3>
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

                            <!-- Form for adding student evaluation -->
                            <form action="userData/ctrl.add.eval.php?student_id=<?php echo htmlspecialchars($student_id); ?>" method="POST" enctype="multipart/form-data">
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Student Image Section -->
                                        <div class="col-md-3 text-center">
                                            <?php
                                            if ($student_image) {
                                                echo '<img src="data:image/jpeg;base64,' . $student_image . '" class="img-fluid rounded-circle shadow-lg" alt="Student Image" style="margin-top: 15px; width: 150px; height: 150px; transition: transform 0.3s;">';
                                            } else {
                                                echo '<img src="../../docs/assets/img/user2.png" class="img-fluid rounded-circle shadow-lg" alt="Default User Image" style="margin-top: 15px; width: 150px; height: 150px; transition: transform 0.3s;">';
                                            }
                                            ?>
                                        </div>

                                        <!-- Evaluation Details Section -->
                                        <div class="col-md-8">
                                            <div class="card mb-3 mt-3">
                                                <div class="card-header bg-olive text-white text-center" style="padding: 8px 20px; font-size: 18px;">
                                                    <span><b>Evaluation Details</b></span>
                                                </div>

                                                <div class="card-body" style="background: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="fullname">Full Name</label>
                                                                <input type="text" id="fullname" class="form-control" value="<?php echo $full_name; ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="course_level">Program and Level</label>
                                                                <input type="text" id="course_level" class="form-control" value="<?php echo $course_abv . ' - ' . $level_name; ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="date">Date of Assessment</label>
                                                                <input type="date" name="date" class="form-control" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="exam">Nature of Exam</label>
                                                                <input type="text" name="exam" class="form-control" required placeholder="Nature of Exam">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="test">Name of Test</label>
                                                                <input type="text" name="test" class="form-control" required placeholder="Name of Test">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="result">Key Result</label>
                                                                <input type="text" name="result" class="form-control" required placeholder="Key Result">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="description">Description</label>
                                                                <textarea name="description" class="form-control" id="description" rows="5" required></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group mt-5 text-center">
                                                        <button type="submit" class="btn bg-primary" name="submit">Submit</button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
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

<?php
mysqli_close($conn);
?>