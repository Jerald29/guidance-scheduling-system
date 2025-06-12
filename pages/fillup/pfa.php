<?php
require '../../includes/session.php';
require_once '../../includes/encryption.php';

// Verify if a student account is logged in
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'Student') {
    header("Location: ../dashboard/index.php");
    exit();
}

// Fetch student data
$student_id = $_SESSION['id'];

// Modified query without Program, Level, Contact Number, and Email
$query = "SELECT 
            CONCAT(stud.student_lname, ', ', stud.student_fname, ' ', stud.student_mname) AS full_name,
            pfa.pfa_date, pfa.pfa_age, pfa.ref_id, pfa.ref_other, pfa.nos_id, pfa.problem_discuss, pfa.counselor_name,
            info.info_id, pfa.pfa_id
          FROM tbl_students stud
          LEFT JOIN tbl_pfa_forms pfa ON stud.student_id = pfa.student_id
          LEFT JOIN tbl_stud_info AS info ON stud.student_id = info.student_id
          WHERE stud.student_id = ?";

// Prepare the statement
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $student_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the student data
$student_data = $result->fetch_assoc();

$fullname = $student_data['full_name'];
$pfa_date = $student_data['pfa_date'];
$pfa_age = $student_data['pfa_age'];
$ref_id = $student_data['ref_id'];
$ref_other = $student_data['ref_other'];
$nos_id = $student_data['nos_id'];
$problem_discuss = $student_data['problem_discuss'];
$counselor_name = $student_data['counselor_name'];
$info_id = $student_data['info_id'];
$pfa_id = $student_data['pfa_id'];

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PFA Form | GCS Bacoor</title>
    <link rel="icon" href="../../docs/assets/img/gcs-bac.png">
    <?php include '../../includes/links.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        function autoDismissMessage(id, timeout) {
            var element = document.getElementById(id);
            if (element) {
                setTimeout(function() {
                    element.style.display = 'none'; 
                }, timeout);
            }
        }

        autoDismissMessage('successMessage', 2000);
        autoDismissMessage('errorMessage', 2000);
    });
    </script>
</head>
<body class="hold-transition layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <?php include '../../includes/navbar.php' ?>
        <?php include '../../includes/sidebar.php' ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Psychological First Aid Form</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../dashboard/index.php">Home</a></li>
                                <li class="breadcrumb-item active">PFA Form</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <section class="content">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                        <!-- Reminder for Students -->
                        <div class="container">
                        <div role="alert" class="slide-in" style="font-size: 16px; line-height: 1.5; text-align: justify; background-color: #e9f7fc; border-left: 5px solid #17a2b8; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); border-radius: 5px; padding: 15px;">
                            <strong style="color: #0d6efd;">Reminder:</strong> Kindly ensure that your <strong style="color: #0d6efd; font-weight: bold;">Student Profile Form</strong> is filled out before moving forward with this form.
                            <style>
                                .slide-in {
                                    animation: slideInExpand 1.5s forwards;
                                }

                                @keyframes slideInExpand {
                                    from {
                                        transform: translateX(-100%);
                                        opacity: 0; 
                                    }
                                    to {
                                        transform: translateX(0); 
                                        opacity: 1; 
                                    }
                                }
                            </style>
                        </div>
                        </div>
                            <?php include '../../includes/message.php'; ?>
                            <div class="card">
                                <form action="userData/ctrl.pfa.php" method="POST">
                                    <div class="card-header bg-white">
                                        <h3 class="card-title"><b>Psychological First Aid</b></h3>
                                    </div>
                                    <div class="card-body">

                                        <div class="row mb-1">
                                            <div class="col-sm-7 mb-2">
                                                <label for="fullname">Name</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                    </div>
                                                    <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>" readonly style="background-color: white;">
                                                </div>
                                            </div>
                                            <div class="col-sm-5 mb-2">
                                                <label for="date">Date<span style="color: red;"> *</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                    </div>
                                                    <input type="date" class="form-control" id="date" name="date" value="<?php echo htmlspecialchars($pfa_date); ?>" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-1">
                                            <div class="col-sm-2 mb-2">
                                                <label for="pfa_age">Age<span style="color: red;"> *</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                    </div>
                                                    <input type="number" min="15" max="30" class="form-control" id="pfa_age" name="pfa_age" placeholder="Age" value="<?php echo htmlspecialchars($pfa_age); ?>" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-1">
                                            <div class="col-sm-3 mb-2">
                                                <label for="ref_id">Referred by<span style="color: red;"> *</span></label>
                                                <div class="input-group">
                                                    <select class="form-control" id="ref_id" name="ref_id" required>
                                                        <option value="">Select Referral</option>
                                                        <?php
                                                        // Fetch data from tbl_referrals
                                                        $query = "SELECT ref_id, ref_name FROM tbl_referrals";
                                                        $result = $conn->query($query);

                                                        if ($result->num_rows > 0) {
                                                            while ($row = $result->fetch_assoc()) {
                                                                $selected = ($row['ref_id'] == $ref_id) ? 'selected' : '';
                                                                echo '<option value="' . htmlspecialchars($row['ref_id']) . '" ' . $selected . '>' . htmlspecialchars($row['ref_name']) . '</option>';
                                                            }
                                                        } else {
                                                            echo '<option value="">No referrals available</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 mb-2" id="ref_other_field" style="display: <?php echo ($ref_id == 5) ? 'block' : 'none'; ?>;">
                                                <label for="ref_other"><span style="color: white;">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="ref_other" name="ref_other" placeholder="Specify" value="<?php echo htmlspecialchars($ref_other); ?>">
                                                </div>
                                            </div>
                                            <script>
                                                document.getElementById('ref_id').addEventListener('change', function () {
                                                    const otherField = document.getElementById('ref_other_field');
                                                    const refOtherInput = document.getElementById('ref_other');
                                                    
                                                    if (this.value == 5) {
                                                        otherField.style.display = 'block';
                                                        refOtherInput.setAttribute('required', 'required');
                                                    } else {
                                                        otherField.style.display = 'none';
                                                        refOtherInput.removeAttribute('required');
                                                        refOtherInput.value = '';
                                                    }
                                                });
                                            </script>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-sm-4 mb-2">
                                                <label for="nos_id">Number of Session/s attended<span style="color: red;"> *</span></label>
                                                <div class="input-group">
                                                    <select class="form-control" id="nos_id" name="nos_id" required>
                                                        <option value="">Select Number of Sessions</option>
                                                        <?php
                                                        // Fetch data from tbl_sessions
                                                        $query = "SELECT nos_id, nos_name FROM tbl_sessions";
                                                        $result = $conn->query($query);

                                                        if ($result->num_rows > 0) {
                                                            while ($row = $result->fetch_assoc()) {
                                                                // Check if the session id matches the previously submitted value
                                                                $selected = ($row['nos_id'] == $nos_id) ? 'selected' : '';
                                                                echo '<option value="' . htmlspecialchars($row['nos_id']) . '" ' . $selected . '>' . htmlspecialchars($row['nos_name']) . '</option>';
                                                            }
                                                        } else {
                                                            echo '<option value="">No sessions available</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-sm-12 mb-2">
                                                <label for="problem_discuss">Problem/s to be discussed<span style="color: red;"> *</span></label>
                                                <div class="input-group">
                                                    <textarea class="form-control" id="problem_discuss" name="problem_discuss" rows="5" placeholder="Describe the problem" required><?php echo htmlspecialchars($problem_discuss); ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-sm-6 mb-2">
                                                <label for="counselor_name">Name of Counselor<span style="color: red;"> *</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                    </div>
                                                    <input type="text" class="form-control" id="counselor_name" name="counselor_name" placeholder="Enter counselor's name" value="<?php echo htmlspecialchars($counselor_name); ?>" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-center">
                                            <div class="mr-3">
                                                <button type="submit" name="submit" class="btn btn-info">Save</button>
                                            </div>

                                            <div>
                                                <?php if (!$info_id): ?>
                                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#pdfIncompleteModal<?= htmlspecialchars($student_id) ?>">View PFA Form</button>

                                                    <!-- Modal for Incomplete PDF -->
                                                    <div class="modal fade" id="pdfIncompleteModal<?= htmlspecialchars($student_id) ?>" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog modal-md">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title text-danger"><b>PDF Not Available</b></h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>The PFA Form for this student is not yet available.<br>
                                                                        Please ensure their Student Profile form is completed to generate the PDF.
                                                                    </p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php elseif ($pfa_id): ?>
                                                    <a href="view.pfa.php?student_id=<?= htmlspecialchars($student_id) ?>" class="btn btn-success">View PFA Form</a>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <?php include '../../includes/script.php'; ?>
    </div>
</body>
</html>
