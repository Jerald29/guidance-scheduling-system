<?php
require '../../includes/session.php';

// Ensure the user is an Administrator / Guidance Advocate
if ($_SESSION['role'] != 'Administrator') {
    $_SESSION['error'] = "Unauthorized access.";
    header('location: ../../login.php');
    exit();
}

// Fetch session ID from the GET request
$session_id = isset($_GET['session_id']) ? $_GET['session_id'] : null;

// Fetch session details
$stmt = $conn->prepare(
    "SELECT s.*, st.student_fname, st.student_lname, st.student_mname, st.img, si.course_id, si.level_id, 
    cou.course_name, cou.course_abv, lev.level_name, lev.level_abv
     FROM tbl_session_forms s 
     JOIN tbl_students st ON s.student_id = st.student_id 
     JOIN tbl_stud_info si ON st.student_id = si.student_id
     LEFT JOIN tbl_courses cou ON si.course_id = cou.course_id
     LEFT JOIN tbl_levels lev ON si.level_id = lev.level_id
     WHERE s.session_id = ?"
);
$stmt->bind_param("i", $session_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $_SESSION['errors'][] = "Session not found.";
    header("Location: ../history.session.php");
    exit();
}

$session = $result->fetch_assoc();

// Extract session details safely
$session_date = htmlspecialchars($session['session_date']);
$session_number = htmlspecialchars($session['session_number']);
$content = htmlspecialchars($session['content'], ENT_QUOTES); // Properly handle quotes
$observation = htmlspecialchars($session['observation'], ENT_QUOTES);
$reco_id = $session['reco_id'];
$session_others = htmlspecialchars($session['session_others']);

// Create the full name for display
$full_name = htmlspecialchars($session['student_lname']) . ', ' . 
    htmlspecialchars($session['student_fname']) . 
    ($session['student_mname'] ? ' ' . htmlspecialchars($session['student_mname']) : '');

// Fetch course and level info
$course_name = htmlspecialchars($session['course_name']);
$level_name = htmlspecialchars($session['level_name']);
$course_abv = htmlspecialchars($session['course_abv']);
$level_abv = htmlspecialchars($session['level_abv']);

// Check if the student has an image
$student_image = !empty($session['img']) ? base64_encode($session['img']) : null;

// Fetch recommendations for the dropdown
$reco_query = "SELECT * FROM tbl_recommendations";
$reco_result = $conn->query($reco_query); 

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Session Notes | GCS Bacoor</title>
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
                            <h1 class="m-0">Edit Session Notes</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../dashboard/index.php">Home</a></li>
                                <li class="breadcrumb-item active">Edit Session Notes</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header bg-olive text-white">
                            <h3 class="card-title">Edit Session Notes for: <b><?php echo $full_name; ?></b></h3>
                        </div>

                        <div class="card-body" style="background: #f4f6f9; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                            <?php
                            if (!empty($_SESSION['errors'])) {
                                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                                foreach ($_SESSION['errors'] as $error) {
                                    echo htmlspecialchars($error) . "<br>";
                                }
                                echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button></div>';
                                unset($_SESSION['errors']);
                            } elseif (!empty($_SESSION['success'])) {
                                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>' . htmlspecialchars($_SESSION['success']) . '</strong>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>';
                                unset($_SESSION['success']);
                            }
                            ?>

                            <form action="userData/ctrl.edit.session.php?session_id=<?php echo htmlspecialchars($session_id); ?>" method="POST">
                                <div class="row">
                                    <div class="col-md-3 text-center">
                                        <?php
                                        if ($student_image) {
                                            echo '<img src="data:image/jpeg;base64,' . $student_image . '" class="img-fluid rounded-circle shadow-lg" alt="Student Image" style="margin-top: 15px; width: 150px; height: 150px; transition: transform 0.3s;">';
                                        } else {
                                            echo '<img src="../../docs/assets/img/user2.png" class="img-fluid rounded-circle shadow-lg" alt="Default User Image" style="margin-top: 15px; width: 150px; height: 150px; transition: transform 0.3s;">';
                                        }
                                        ?>
                                    </div>

                                    <div class="col-md-9">
                                        <div class="card mb-3 mt-3">
                                            <div class="card-header bg-olive text-white text-center" style="padding: 8px 20px; font-size: 18px;">
                                                <span><b>Edit Session Details</b></span>
                                            </div>

                                            <div class="card-body" style="background: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1s);">
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
                                                            <input type="date" name="session_date" class="form-control" value="<?php echo $session_date; ?>" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="session_number">Session Number</label>
                                                            <input type="number" name="session_number" class="form-control" value="<?php echo $session_number; ?>" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="content">Content</label>
                                                            <textarea name="content" class="form-control" rows="5" required><?php echo htmlspecialchars_decode($content); ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="observation">Observation/s</label>
                                                            <textarea name="observation" class="form-control" rows="5" required><?php echo htmlspecialchars_decode($observation); ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group text-end">
                                                            <label for="reco_id">Recommendation</label>
                                                            <select name="reco_id" id="reco_id" class="form-control" required onchange="toggleOthersField()">
                                                                <option value="" disabled>Select Recommendation</option>
                                                                <?php
                                                                $reco_query = "SELECT * FROM tbl_recommendations";
                                                                $reco_result = $conn->query($reco_query);
                                                                while ($row = $reco_result->fetch_assoc()) {
                                                                    $selected = ($row['reco_id'] == $reco_id) ? 'selected' : '';
                                                                    echo "<option value='" . $row['reco_id'] . "' $selected>" . htmlspecialchars($row['reco_name']) . "</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3" id="others-container" style="display: <?php echo ($reco_id == 4) ? 'block' : 'none'; ?>;">
                                                        <div class="form-group" style="margin-top: 32px;">
                                                            <input type="text" name="session_others" id="session_others" class="form-control" placeholder="Specify" value="<?php echo isset($session_others) ? htmlspecialchars($session_others) : ''; ?>">
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
                                                    <button type="submit" class="btn bg-primary" name="submit">Update Session Notes</button>
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
