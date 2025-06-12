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

// Check if the evaluation ID is present
if (!$eval_id) {
    $_SESSION['errors'][] = "No evaluation ID provided.";
    header("Location: ../evaluation/list.eval.php");
    exit();
}

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
    <title>View Student Evaluation | GCS Bacoor</title>
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
                            <h1 class="m-0">View Student Evaluation</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../dashboard/index.php">Home</a></li>
                                <li class="breadcrumb-item active">View Evaluation</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header bg-olive text-white">
                            <h3 class="card-title">Evaluation Details for: <b><?php echo $full_name; ?></b></h3>
                        </div>

                        <div class="card-body" style="margin-top: 20px;">
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
                                <div class="col-md-7">
                                    <div class="card mb-3 mt-3">
                                        <div class="card-header bg-olive text-white text-center" style="padding: 8px 20px; font-size: 18px;">
                                            <span><b>Evaluation Information</b></span>
                                        </div>
                                        <div class="card-body" style="background: #ffffff; padding: 15px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);">
                                            <p class="mb-2"><strong>Fullname:</strong> <span class="text-dark"><?php echo htmlspecialchars($full_name); ?></span></p>
                                            <p class="mb-2"><strong>Program and Level:</strong> <span class="text-dark"><?php echo htmlspecialchars($course_name . ' - ' . $level_name); ?></span></p>
                                            <p class="mb-2"><strong>Date of Assessment:</strong> <span class="text-dark"><?php echo date('F j, Y', strtotime($evaluation['date'])); ?></span></p>
                                            <p class="mb-2"><strong>Nature of Exam:</strong> <span class="text-dark"><?php echo htmlspecialchars($evaluation['exam']); ?></span></p>
                                            <p class="mb-2"><strong>Name of Test:</strong> <span class="text-dark"><?php echo htmlspecialchars($evaluation['test']); ?></span></p>
                                            <p class="mb-2"><strong>Key Result:</strong> <span class="text-dark"><?php echo htmlspecialchars($evaluation['result']); ?></span></p>
                                        </div>
                                    </div>

                                    <!-- Collapsible Description Card -->
                                    <div class="card mb-3">
                                        <div id="descriptionHeader" class="card-header bg-olive text-white d-flex justify-content-between align-items-center" 
                                            style="cursor: pointer; padding: 8px 20px; font-size: 18px;" 
                                            data-toggle="collapse" data-target="#descriptionBox">
                                            <span><b>Description</b></span>
                                            <i class="fa fa-chevron-down ml-auto arrow-icon"></i>
                                        </div>
                                        <div id="descriptionBox" class="collapse">
                                            <div class="card-body" style="background: #f7f9fc;">
                                                <p class="mb-0"><?php echo nl2br(htmlspecialchars($evaluation['description'])); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <style>
                                        .arrow-icon {
                                            transition: transform 0.3s ease;
                                        }
                                        .rotate-arrow {
                                            transform: rotate(180deg);
                                        }
                                    </style>
                                    <script>
                                        // List of header IDs
                                        const headers = [
                                            "descriptionHeader",
                                            "contentHeader",
                                            "observationHeader"
                                        ];

                                        // Add click event listeners to each header to toggle the rotation
                                        headers.forEach(headerId => {
                                            document.getElementById(headerId).addEventListener("click", function() {
                                                const arrowIcon = this.querySelector(".arrow-icon");
                                                arrowIcon.classList.toggle("rotate-arrow");
                                            });
                                        });
                                    </script>
                                </div>

                            </div>
                        </div> 
                    </div>
                </div>
                <style>
                    .card-header:hover {
                        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
                    }
                    img.img:hover {
                        transform: scale(1.05);
                    }
                </style>
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