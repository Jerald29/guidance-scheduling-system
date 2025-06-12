<?php
include '../../includes/session.php';
require_once '../../includes/encryption.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure the user is an Administrator / Guidance Advocate
if ($_SESSION['role'] != 'Administrator') {
    $_SESSION['error'] = "Unauthorized access.";
    header('Location: ../../login.php');
    exit();
}

// Fetch session details based on student_id
$student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : null;

if (!$student_id) {
    $_SESSION['error'] = "Invalid student ID.";
    header('Location: list.session.php');
    exit();
}

// Prepare and execute the SQL statement to fetch all sessions of a student
$sql = "
    SELECT s.*, 
           st.student_fname, 
           st.student_mname, 
           st.student_lname,
           st.img,  
           r.reco_name    
    FROM tbl_session_forms s 
    JOIN tbl_students st ON s.student_id = st.student_id 
    LEFT JOIN tbl_stud_info si ON st.student_id = si.student_id  
    LEFT JOIN tbl_recommendations r ON s.reco_id = r.reco_id
    WHERE s.student_id = ?
    ORDER BY s.session_date ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $student_id);
$stmt->execute();
$result = $stmt->get_result();

$sessions = [];
while ($row = $result->fetch_assoc()) {
    $sessions[] = $row;
}

// Clear session messages
$error = $_SESSION['error'] ?? null;
$success = $_SESSION['success'] ?? null;
unset($_SESSION['error'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View History | GCS Bacoor</title>
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
                            <h1 class="m-0">Session History</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../dashboard/index.php">Home</a></li>
                                <li class="breadcrumb-item active">View History</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-sm">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Session History for: 
                                <b>
                                    <?php 
                                    if (!empty($sessions)) {
                                        echo htmlspecialchars($sessions[0]['student_lname'] . ', ' . $sessions[0]['student_fname'] . ' ' . $sessions[0]['student_mname']);
                                    } else {
                                        // Fetch student details separately to ensure name is displayed
                                        $student_sql = "SELECT student_fname, student_mname, student_lname FROM tbl_students WHERE student_id = ?";
                                        $student_stmt = $conn->prepare($student_sql);
                                        $student_stmt->bind_param('i', $student_id);
                                        $student_stmt->execute();
                                        $student_result = $student_stmt->get_result();
                                        $student = $student_result->fetch_assoc();

                                        if ($student) {
                                            echo htmlspecialchars($student['student_lname'] . ', ' . $student['student_fname'] . ' ' . $student['student_mname']);
                                        } else {
                                            echo "Unknown Student";
                                        }

                                        echo " - No Sessions Found";
                                    }
                                    ?>
                                </b>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive table-hover">
                                <?php if ($success): ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>Success!</strong> <?= htmlspecialchars($success) ?>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                <?php endif; ?>

                                <?php if ($error): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>Error!</strong> <?= htmlspecialchars($error) ?>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                <?php endif; ?>
                                <table class="table table-bordered table-sm text-center">
                                    <thead class="bg-gray text-white">
                                        <tr>
                                            <th class="col-1">Session No.</th>
                                            <th class="col-2">Date</th>
                                            <th class="col-5">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($sessions)): ?>
                                            <?php foreach ($sessions as $session): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($session['session_number']); ?></td>
                                                    <td><?= date("F j, Y", strtotime($session['session_date'])); ?></td>
                                                    <td>
                                                        <div class="button-group ">
                                                            <a class="btn btn-success btn-sm rounded mr-1 btn-view" 
                                                                data-session_number="<?= $session['session_number']; ?>" 
                                                                data-session_date="<?= date("F j, Y", strtotime($session['session_date'])); ?>"
                                                                data-content="<?= $session['content']; ?>"
                                                                data-observation="<?= $session['observation']; ?>"
                                                                data-reco_name="<?= $session['reco_name']; ?>"
                                                                data-session_others="<?= $session['session_others']; ?>"
                                                            >
                                                                View
                                                            </a>
                                                            <a href="view.form.php?session_id=<?= htmlspecialchars($session['session_id']) ?>" 
                                                            class="btn btn-info btn-sm rounded mr-1">
                                                                Print
                                                            </a>
                                                            <a href="edit.session.php?session_id=<?= htmlspecialchars($session['session_id']) ?>" class="btn btn-warning btn-sm rounded mr-1">
                                                                Edit
                                                            </a>
                                                            <button class="btn btn-danger btn-sm rounded" data-toggle="modal" data-target="#deleteModal<?= htmlspecialchars($session['session_id']) ?>">
                                                                Delete
                                                            </button>

                                                            <!-- Delete Modal -->
                                                            <div class="modal fade" id="deleteModal<?= htmlspecialchars($session['session_id']) ?>" tabindex="-1" role="dialog">
                                                                <div class="modal-dialog modal-md">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h4 class="modal-title text-danger"><b>Delete Session</b></h4>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p>Are you sure you want to delete this session?</p>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                            <a href="userData/ctrl.del.session.php?session_id=<?= htmlspecialchars($session['session_id']) ?>" class="btn btn-danger">
                                                                                Delete
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3">No session history available.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-olive">
                            <h5 class="modal-title" id="viewModalLabel"><strong>Session Details</strong></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-6">
                                        <p><strong>Session Number:</strong> <span id="session_number"></span></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <p><strong>Session Date:</strong> <span id="session_date"></span></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-12">
                                        <p><strong>Content:</strong></p>
                                        <p id="content" class="text-justify"></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-12">
                                        <p><strong>Observation:</strong></p>
                                        <p id="observation" class="text-justify"></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-6">
                                        <p><strong>Recommendation:</strong> <span id="reco_name"></span></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <p><strong>Others:</strong> <span id="session_others"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <?php include '../../includes/footer.php'; ?>
    </div>

    <?php include '../../includes/script.php'; ?>
</body>

</html>

<?php
$stmt->close();
$conn->close();  // Close the database connection
?>
