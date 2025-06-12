<?php
require '../../includes/session.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure the user is an Administrator / Guidance Advocate
if ($_SESSION['role'] != 'Administrator') {
    $_SESSION['error'] = "Unauthorized access.";
    header('Location: ../../login.php');
    exit();
}

// Fetch evaluation ID from the GET request
$eval_id = isset($_GET['eval_id']) ? intval($_GET['eval_id']) : null;

// Fetch evaluation and student details along with program and level names in one query
$query = "
    SELECT e.*, s.*, si.*, cou.course_name, cou.course_abv, lev.level_name, lev.level_abv
    FROM tbl_evaluations e
    JOIN tbl_students s ON e.student_id = s.student_id
    JOIN tbl_stud_info si ON s.student_id = si.student_id
    LEFT JOIN tbl_courses cou ON si.course_id = cou.course_id
    LEFT JOIN tbl_levels lev ON si.level_id = lev.level_id
    WHERE e.eval_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $eval_id);
$stmt->execute();
$result = $stmt->get_result();
$evaluation = $result->fetch_assoc();

if (!$evaluation) {
    $_SESSION['error'] = "Evaluation not found.";
    header('Location: list.eval.php');
    exit();
}

// Clear the session messages after use
$error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
unset($_SESSION['error']);
unset($_SESSION['success']);

// Prepare student data
$full_name = $evaluation['student_lname'] . ', ' . $evaluation['student_fname'] . ($evaluation['student_mname'] ? ' ' . $evaluation['student_mname'] : '');
$course_name = htmlspecialchars($evaluation['course_name']);
$level_name = htmlspecialchars($evaluation['level_name']);
$course_abv = htmlspecialchars($evaluation['course_abv']);
$level_abv = htmlspecialchars($evaluation['level_abv']);
$date = htmlspecialchars($evaluation['date']);
$exam = htmlspecialchars($evaluation['exam']);
$test = htmlspecialchars($evaluation['test']);
$result_text = htmlspecialchars($evaluation['result']);
$description = htmlspecialchars($evaluation['description']);

// Check if the student has an image
$student_image = !empty($evaluation['img']) ? base64_encode($evaluation['img']) : null;

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Student Evaluation | GCS Bacoor</title>
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
                            <h1 class="m-0">Edit Student Evaluation</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../dashboard/index.php">Home</a></li>
                                <li class="breadcrumb-item active">Edit Evaluation</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header bg-olive text-white">
                            <h3 class="card-title">Edit Evaluation for Student: <b><?php echo $full_name; ?></b></h3>
                        </div>

                        <div class="card-body">
                            <!-- Display success or error messages -->
                            <?php
                            if (!empty($error)) {
                                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                                echo $error;
                                echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button></div>';
                            } elseif (!empty($success)) {
                                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>' . $success . '</strong>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>';
                            }
                            ?>

                            <!-- Form for editing student evaluation -->
                            <form action="userData/ctrl.edit.eval.php?eval_id=<?php echo htmlspecialchars($eval_id); ?>" method="POST">
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

                                        <!-- Evaluation Information Section -->
                                        <div class="col-md-8">
                                            <div class="card mb-3 mt-3">
                                                <div class="card-header bg-olive text-white text-center" style="padding: 8px 20px; font-size: 18px;">
                                                    <span><b>Evaluation Information</b></span>
                                                </div>

                                                <div class="card-body">
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
                                                                <input type="date" name="date" class="form-control" required value="<?php echo $date; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="exam">Nature of Exam</label>
                                                                <input type="text" name="exam" class="form-control" required placeholder="Nature of Exam" value="<?php echo $exam; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="test">Name of Test</label>
                                                                <input type="text" name="test" class="form-control" required placeholder="Name of Test" value="<?php echo $test; ?>">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="result">Key Result</label>
                                                                <input type="text" name="result" class="form-control" required placeholder="Key Result" value="<?php echo $result_text; ?>">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="description">Description</label>
                                                                <textarea name="description" class="form-control" id="description" rows="5" required><?php echo $description; ?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group mt-5 text-center">
                                                        <button type="submit" class="btn bg-primary" name="submit">Update</button>
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
