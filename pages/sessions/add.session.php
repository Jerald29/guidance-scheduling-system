<?php
require '../../includes/session.php';

// Ensure the user is an Administrator / Guidance Advocate
if ($_SESSION['role'] != 'Administrator') {
    $_SESSION['error'] = "Unauthorized access.";
    header('location: ../../login.php');
    exit();
}

$student_id = isset($_GET['student_id']) ? (int) $_GET['student_id'] : null;

// Check if student ID is valid
if ($student_id <= 0) {
    header("Location: ../sessions/list.session.php");
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

// Check if the student exists in the database
if ($result->num_rows == 0) {
    header("Location: ../sessions/list.session.php");
    exit();
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
    <title>Add Session Notes | GCS Bacoor</title>
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
                            <h1 class="m-0">Add Session Notes</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../dashboard/index.php">Home</a></li>
                                <li class="breadcrumb-item active">Add Session Notes</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            
            <section class="content">
                <div class="container-fluid">
                    <!-- Display Error and Success Messages -->
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
                    <div class="card">
                        <div class="card-header bg-olive text-white">
                            <h3 class="card-title">Add Session Notes for: <b><?php echo $full_name; ?></b></h3>
                        </div>

                        <div class="card-body" style="background: #f4f6f9; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                            <!-- Form to Add Session Notes -->
                            <form action="userData/ctrl.add.session.php?student_id=<?php echo htmlspecialchars($student_id); ?>" method="POST">
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

                                        <!-- Session Details Section -->
                                        <div class="col-md-9">
                                            <div class="card mb-3 mt-3">
                                                <div class="card-header bg-olive text-white text-center" style="padding: 8px 20px; font-size: 18px;">
                                                    <span><b>Session Details</b></span>
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
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="session_date">Session Date</label>
                                                                <input type="date" name="session_date" class="form-control" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="session_number">Session Number</label>
                                                                <input type="number" name="session_number" class="form-control" required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="content">Content</label>
                                                                <textarea name="content" class="form-control" rows="5" required></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="observation">Observation/s</label>
                                                                <textarea name="observation" class="form-control" rows="5" required></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group text-end">
                                                                <label for="reco_id">Recommendation</label>
                                                                <select name="reco_id" id="reco_id" class="form-control" required onchange="toggleOthersField()">
                                                                    <option value="" disabled selected>Select</option>
                                                                    <?php
                                                                    $reco_query = "SELECT * FROM tbl_recommendations";
                                                                    $reco_result = $conn->query($reco_query);
                                                                    while ($row = $reco_result->fetch_assoc()) {
                                                                        echo "<option value='" . $row['reco_id'] . "'>" . htmlspecialchars($row['reco_name']) . "</option>";
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3" id="others-container" style="display: none;">
                                                            <div class="form-group" style="margin-top: 32px;">
                                                                <input type="text" name="session_others" id="session_others" class="form-control" placeholder="Specify">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <script>
                                                        function toggleOthersField() {
                                                            const recoSelect = document.getElementById("reco_id");
                                                            const othersContainer = document.getElementById("others-container");

                                                            if (recoSelect.value === "4") {
                                                                othersContainer.style.display = "block";
                                                            } else {
                                                                othersContainer.style.display = "none";
                                                            }
                                                        }
                                                    </script>

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
