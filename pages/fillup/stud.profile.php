<?php
require '../../includes/session.php';
require_once '../../includes/encryption.php';

// Verify if a student account is logged in
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'Student') {
    header("Location: ../dashboard/index.php");
    exit();
}

// Fetch data for the form if necessary
$student_id = $_SESSION['id'];

// Use prepared statements
$student_query = "SELECT * FROM tbl_students WHERE student_id = ?";
$student_stmt = $conn->prepare($student_query);
$student_stmt->bind_param('i', $student_id);
$student_stmt->execute();
$student_result = $student_stmt->get_result();
$student_data = $student_result->fetch_assoc();
$student_email = $student_data['email'] ?? ''; 

// Fetch student information
$info_query = "SELECT * FROM tbl_stud_info WHERE student_id = ?";
$info_stmt = $conn->prepare($info_query);
$info_stmt->bind_param('i', $student_id);
$info_stmt->execute();
$info_result = $info_stmt->get_result();
$info_data = $info_result->fetch_assoc();

$student_stmt->close();
$info_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student's Profile Form | GCS Bacoor</title>
    <link rel="icon" href="../../docs/assets/img/gcs-bac.png">
    <?php include '../../includes/links.php'; ?>
    <style>
        /* Define the custom offset class */
        .offset-sm-half {
            margin-left: 4.167%;
        }
        /* Custom offset class for 2.5 columns */
        .offset-sm-2-5 {
            margin-left: 20.825%;
        }
        /* Custom offset class for 1.1 columns */
        .offset-sm-1-1 {
            margin-left: 9.163%;
        }
    </style>

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

        autoDismissMessage('successMessage', 5000);
        autoDismissMessage('errorMessage', 5000);
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
                            <h1 class="m-0">Student's Profile Form</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../dashboard/index.php">Home</a></li>
                                <li class="breadcrumb-item active">Student's Profile Form</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                        <!-- Note for Students -->
                        <div class="container">
                        <div role="alert" class="slide-in" style="font-size: 16px; line-height: 1.5; text-align: justify; background-color: #e9f7fc; border-left: 5px solid #17a2b8; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); border-radius: 5px; padding: 15px;">
                            <strong style="color: #0d6efd;">Reminder:</strong> Your personal data is securely protected using the 
                            <strong style="color: #0d6efd; font-weight: bold;">Advanced Encryption Standard (AES)</strong> algorithm for privacy.
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
                            <div class="card">
                                <?php include '../../includes/message.php'; ?>
                                <form action="userData/ctrl.stud.profile.php" method="POST">
                                    <div class="card-header">
                                        <h3 class="card-title"><b>Student's Profile</b></h3>
                                    </div>
                                    <div class="card-body">

                                        <div class="row mb-1">
                                            <div class="col-sm-12 mb-2">
                                                <h5><b>I. Personal Information</b></h5>
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <label for="lastname">Last Name</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                    </div>
                                                    <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo htmlspecialchars($student_data['student_lname']); ?>" readonly>
                                                </div>
                                            </div>

                                            <div class="col-sm-4 mb-2">
                                                <label for="firstname">First Name</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                    </div>
                                                    <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo htmlspecialchars($student_data['student_fname']); ?>" readonly>
                                                </div>
                                            </div>

                                            <div class="col-sm-4 mb-2">
                                                <label for="midname">Middle Name</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                    </div>
                                                    <input type="text" class="form-control" id="midname" name="midname" value="<?php echo htmlspecialchars($student_data['student_mname']); ?>" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-1">
                                            <div class="col-sm-3 mb-2">
                                                <label for="campus">Campus</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                    </div>
                                                    <select class="form-control" id="campus" name="campus">
                                                        <option value="" disabled selected>Select Campus</option>
                                                        <?php
                                                        $campuses_query = "SELECT * FROM tbl_campuses";
                                                        $campuses_result = mysqli_query($conn, $campuses_query);

                                                        while ($campus = mysqli_fetch_assoc($campuses_result)) {
                                                            $selected = ($info_data['campus_id'] == $campus['campus_id']) ? 'selected' : '';
                                                            echo "<option value='{$campus['campus_id']}' $selected>{$campus['campus_name']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-6 mb-2">
                                                <label for="course">Program</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                    </div>
                                                    <select class="form-control" id="course" name="course">
                                                        <option value="" disabled selected>Select Program</option>
                                                        <?php
                                                        $courses_query = "SELECT * FROM tbl_courses ORDER BY course_name ASC";
                                                        $courses_result = mysqli_query($conn, $courses_query);

                                                        while ($course = mysqli_fetch_assoc($courses_result)) {
                                                            $selected = ($info_data['course_id'] == $course['course_id']) ? 'selected' : '';
                                                            echo "<option value='{$course['course_id']}' $selected>{$course['course_name']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-3 mb-2">
                                                <label for="level">Level</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                    </div>
                                                    <select class="form-control" id="level" name="level">
                                                        <option value="" disabled selected>Select Level</option>
                                                        <?php
                                                        $levels_query = "SELECT * FROM tbl_levels";
                                                        $levels_result = mysqli_query($conn, $levels_query);

                                                        while ($level = mysqli_fetch_assoc($levels_result)) {
                                                            $selected = ($info_data['level_id'] == $level['level_id']) ? 'selected' : '';
                                                            echo "<option value='{$level['level_id']}' $selected>{$level['level_name']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <?php
                                            // Helper function to decrypt data
                                            function decrypt_if_needed($encrypted_value) {
                                                return $encrypted_value ? decrypt($encrypted_value) : '';
                                            }

                                            // Retrieve the encrypted fields from the database
                                            $encrypted_address = $info_data['address'] ?? '';
                                            $encrypted_birthdate = $info_data['birthdate'] ?? '';
                                            $encrypted_birthplace = $info_data['birthplace'] ?? '';
                                            $encrypted_citizenship = $info_data['citizenship'] ?? '';
                                            $encrypted_height = $info_data['height'] ?? '';
                                            $encrypted_weight = $info_data['weight'] ?? '';
                                            $encrypted_mobile_no = $info_data['mobile_no'] ?? '';
                                            $encrypted_tel_no = $info_data['tel_no'] ?? '';
                                            $encrypted_father_name = $info_data['father_name'] ?? null;
                                            $encrypted_father_occu = $info_data['father_occu'] ?? null;
                                            $encrypted_father_contact = $info_data['father_contact'] ?? null;
                                            $encrypted_mother_name = $info_data['mother_name'] ?? null;
                                            $encrypted_mother_occu = $info_data['mother_occu'] ?? null;
                                            $encrypted_mother_contact = $info_data['mother_contact'] ?? null;
                                            $encrypted_sib1_name = $info_data['sib1_name'] ?? null;
                                            $encrypted_sib2_name = $info_data['sib2_name'] ?? null;
                                            $encrypted_sib3_name = $info_data['sib3_name'] ?? null;
                                            $encrypted_sib4_name = $info_data['sib4_name'] ?? null;
                                            $encrypted_sib5_name = $info_data['sib5_name'] ?? null;
                                            $encrypted_sib6_name = $info_data['sib6_name'] ?? null;
                                            $encrypted_sib7_name = $info_data['sib7_name'] ?? null;
                                            $encrypted_sib1_occu = $info_data['sib1_occu'] ?? null;
                                            $encrypted_sib2_occu = $info_data['sib2_occu'] ?? null;
                                            $encrypted_sib3_occu = $info_data['sib3_occu'] ?? null;
                                            $encrypted_sib4_occu = $info_data['sib4_occu'] ?? null;
                                            $encrypted_sib5_occu = $info_data['sib5_occu'] ?? null;
                                            $encrypted_sib6_occu = $info_data['sib6_occu'] ?? null;
                                            $encrypted_sib7_occu = $info_data['sib7_occu'] ?? null;
                                            $encrypted_sib1_contact = $info_data['sib1_contact'] ?? null;
                                            $encrypted_sib2_contact = $info_data['sib2_contact'] ?? null;
                                            $encrypted_sib3_contact = $info_data['sib3_contact'] ?? null;
                                            $encrypted_sib4_contact = $info_data['sib4_contact'] ?? null;
                                            $encrypted_sib5_contact = $info_data['sib5_contact'] ?? null;
                                            $encrypted_sib6_contact = $info_data['sib6_contact'] ?? null;
                                            $encrypted_sib7_contact = $info_data['sib7_contact'] ?? null;
                                            $encrypted_gs_name = $info_data['gs_name'] ?? null;
                                            $encrypted_gs_type = $info_data['gs_type'] ?? null;
                                            $encrypted_gs_year = $info_data['gs_year'] ?? null;
                                            $encrypted_gs_honor = $info_data['gs_honor'] ?? null;
                                            $encrypted_jhs_name = $info_data['jhs_name'] ?? null;
                                            $encrypted_jhs_type = $info_data['jhs_type'] ?? null;
                                            $encrypted_jhs_year = $info_data['jhs_year'] ?? null;
                                            $encrypted_jhs_honor = $info_data['jhs_honor'] ?? null;
                                            $encrypted_shs_name = $info_data['shs_name'] ?? null;
                                            $encrypted_shs_type = $info_data['shs_type'] ?? null;
                                            $encrypted_shs_year = $info_data['shs_year'] ?? null;
                                            $encrypted_shs_honor = $info_data['shs_honor'] ?? null;
                                            $encrypted_vs_name = $info_data['vs_name'] ?? null;
                                            $encrypted_vs_type = $info_data['vs_type'] ?? null;
                                            $encrypted_vs_year = $info_data['vs_year'] ?? null;
                                            $encrypted_vs_honor = $info_data['vs_honor'] ?? null;
                                            $encrypted_college_name = $info_data['college_name'] ?? null;
                                            $encrypted_college_type = $info_data['college_type'] ?? null;
                                            $encrypted_college_year = $info_data['college_year'] ?? null;
                                            $encrypted_college_honor = $info_data['college_honor'] ?? null;
                                            $encrypted_org1_name = $info_data['org1_name'] ?? null;
                                            $encrypted_org1_serve = $info_data['org1_serve'] ?? null;
                                            $encrypted_org1_date = $info_data['org1_date'] ?? null;
                                            $encrypted_org2_name = $info_data['org2_name'] ?? null;
                                            $encrypted_org2_serve = $info_data['org2_serve'] ?? null;
                                            $encrypted_org2_date = $info_data['org2_date'] ?? null;
                                            $encrypted_org3_name = $info_data['org3_name'] ?? null;
                                            $encrypted_org3_serve = $info_data['org3_serve'] ?? null;
                                            $encrypted_org3_date = $info_data['org3_date'] ?? null;
                                            $encrypted_marital_others = $info_data['marital_others'] ?? null;
                                            $encrypted_allowance_others = $info_data['allowance_others'] ?? null;
                                            $encrypted_residence_others = $info_data['residence_others'] ?? null;
                                            $encrypted_dose1_name = $info_data['dose1_name'] ?? null;
                                            $encrypted_dose1_date = $info_data['dose1_date'] ?? null;
                                            $encrypted_dose2_name = $info_data['dose2_name'] ?? null;
                                            $encrypted_dose2_date = $info_data['dose2_date'] ?? null;
                                            $encrypted_boost1_name = $info_data['boost1_name'] ?? null;
                                            $encrypted_boost1_date = $info_data['boost1_date'] ?? null;
                                            $encrypted_boost2_name = $info_data['boost2_name'] ?? null;
                                            $encrypted_boost2_date = $info_data['boost2_date'] ?? null;
                                            $encrypted_vaccination = $info_data['vaccination'] ?? null;
                                            $encrypted_vitamin = $info_data['vitamin'] ?? null;
                                            $encrypted_maintenance = $info_data['maintenance'] ?? null;
                                            $encrypted_vision = $info_data['vision'] ?? null;
                                            $encrypted_vision_specify = $info_data['vision_specify'] ?? null;
                                            $encrypted_hearing = $info_data['hearing'] ?? null;
                                            $encrypted_hearing_specify = $info_data['hearing_specify'] ?? null;
                                            $encrypted_speech = $info_data['speech'] ?? null;
                                            $encrypted_speech_specify = $info_data['speech_specify'] ?? null;
                                            $encrypted_genhealth = $info_data['genhealth'] ?? null;
                                            $encrypted_genhealth_specify = $info_data['genhealth_specify'] ?? null;
                                            $encrypted_health_con = $info_data['health_con'] ?? null;
                                            $encrypted_psychiatrist = $info_data['psychiatrist'] ?? null;
                                            $encrypted_psychiatrist_when = $info_data['psychiatrist_when'] ?? null;
                                            $encrypted_psychiatrist_what = $info_data['psychiatrist_what'] ?? null;
                                            $encrypted_psychologist = $info_data['psychologist'] ?? null;
                                            $encrypted_psychologist_when = $info_data['psychologist_when'] ?? null;
                                            $encrypted_psychologist_what = $info_data['psychologist_what'] ?? null;
                                            $encrypted_counselor = $info_data['counselor'] ?? null;
                                            $encrypted_counselor_when = $info_data['counselor_when'] ?? null;
                                            $encrypted_counselor_what = $info_data['counselor_what'] ?? null;
                                            $encrypted_fav_course = $info_data['fav_course'] ?? null;
                                            $encrypted_extra_others = $info_data['extra_others'] ?? null;
                                            $encrypted_pos_others = $info_data['pos_others'] ?? null;
                                            $encrypted_messenger = $info_data['messenger'] ?? null;
                                            $encrypted_viber = $info_data['viber'] ?? null;
                                            $encrypted_comm_email = $info_data['comm_email'] ?? null;
                                            $encrypted_comm_other = $info_data['comm_other'] ?? null;

                                            // Decrypt fields using the helper function
                                            $decrypted_address = decrypt_if_needed($encrypted_address);
                                            $decrypted_birthdate = decrypt_if_needed($encrypted_birthdate);
                                            $decrypted_birthplace = decrypt_if_needed($encrypted_birthplace);
                                            $decrypted_citizenship = decrypt_if_needed($encrypted_citizenship);
                                            $decrypted_height = decrypt_if_needed($encrypted_height);
                                            $decrypted_weight = decrypt_if_needed($encrypted_weight);
                                            $decrypted_mobile_no = decrypt_if_needed($encrypted_mobile_no);
                                            $decrypted_tel_no = decrypt_if_needed($encrypted_tel_no);
                                            $decrypted_father_name = decrypt_if_needed($encrypted_father_name);
                                            $decrypted_father_occu = decrypt_if_needed($encrypted_father_occu);
                                            $decrypted_father_contact = decrypt_if_needed($encrypted_father_contact);
                                            $decrypted_mother_name = decrypt_if_needed($encrypted_mother_name);
                                            $decrypted_mother_occu = decrypt_if_needed($encrypted_mother_occu);
                                            $decrypted_mother_contact = decrypt_if_needed($encrypted_mother_contact);
                                            $decrypted_sib1_name = decrypt_if_needed($encrypted_sib1_name);
                                            $decrypted_sib2_name = decrypt_if_needed($encrypted_sib2_name);
                                            $decrypted_sib3_name = decrypt_if_needed($encrypted_sib3_name);
                                            $decrypted_sib4_name = decrypt_if_needed($encrypted_sib4_name);
                                            $decrypted_sib5_name = decrypt_if_needed($encrypted_sib5_name);
                                            $decrypted_sib6_name = decrypt_if_needed($encrypted_sib6_name);
                                            $decrypted_sib7_name = decrypt_if_needed($encrypted_sib7_name);
                                            $decrypted_sib1_occu = decrypt_if_needed($encrypted_sib1_occu);
                                            $decrypted_sib2_occu = decrypt_if_needed($encrypted_sib2_occu);
                                            $decrypted_sib3_occu = decrypt_if_needed($encrypted_sib3_occu);
                                            $decrypted_sib4_occu = decrypt_if_needed($encrypted_sib4_occu);
                                            $decrypted_sib5_occu = decrypt_if_needed($encrypted_sib5_occu);
                                            $decrypted_sib6_occu = decrypt_if_needed($encrypted_sib6_occu);
                                            $decrypted_sib7_occu = decrypt_if_needed($encrypted_sib7_occu);
                                            $decrypted_sib1_contact = decrypt_if_needed($encrypted_sib1_contact);
                                            $decrypted_sib2_contact = decrypt_if_needed($encrypted_sib2_contact);
                                            $decrypted_sib3_contact = decrypt_if_needed($encrypted_sib3_contact);
                                            $decrypted_sib4_contact = decrypt_if_needed($encrypted_sib4_contact);
                                            $decrypted_sib5_contact = decrypt_if_needed($encrypted_sib5_contact);
                                            $decrypted_sib6_contact = decrypt_if_needed($encrypted_sib6_contact);
                                            $decrypted_sib7_contact = decrypt_if_needed($encrypted_sib7_contact);
                                            $decrypted_gs_name = decrypt_if_needed($encrypted_gs_name);
                                            $decrypted_gs_type = decrypt_if_needed($encrypted_gs_type);
                                            $decrypted_gs_year = decrypt_if_needed($encrypted_gs_year);
                                            $decrypted_gs_honor = decrypt_if_needed($encrypted_gs_honor);
                                            $decrypted_jhs_name = decrypt_if_needed($encrypted_jhs_name);
                                            $decrypted_jhs_type = decrypt_if_needed($encrypted_jhs_type);
                                            $decrypted_jhs_year = decrypt_if_needed($encrypted_jhs_year);
                                            $decrypted_jhs_honor = decrypt_if_needed($encrypted_jhs_honor);
                                            $decrypted_shs_name = decrypt_if_needed($encrypted_shs_name);
                                            $decrypted_shs_type = decrypt_if_needed($encrypted_shs_type);
                                            $decrypted_shs_year = decrypt_if_needed($encrypted_shs_year);
                                            $decrypted_shs_honor = decrypt_if_needed($encrypted_shs_honor);
                                            $decrypted_vs_name = decrypt_if_needed($encrypted_vs_name);
                                            $decrypted_vs_type = decrypt_if_needed($encrypted_vs_type);
                                            $decrypted_vs_year = decrypt_if_needed($encrypted_vs_year);
                                            $decrypted_vs_honor = decrypt_if_needed($encrypted_vs_honor);
                                            $decrypted_college_name = decrypt_if_needed($encrypted_college_name);
                                            $decrypted_college_type = decrypt_if_needed($encrypted_college_type);
                                            $decrypted_college_year = decrypt_if_needed($encrypted_college_year);
                                            $decrypted_college_honor = decrypt_if_needed($encrypted_college_honor);
                                            $decrypted_org1_name = decrypt_if_needed($encrypted_org1_name);
                                            $decrypted_org1_serve = decrypt_if_needed($encrypted_org1_serve);
                                            $decrypted_org1_date = decrypt_if_needed($encrypted_org1_date);
                                            $decrypted_org2_name = decrypt_if_needed($encrypted_org2_name);
                                            $decrypted_org2_serve = decrypt_if_needed($encrypted_org2_serve);
                                            $decrypted_org2_date = decrypt_if_needed($encrypted_org2_date);
                                            $decrypted_org3_name = decrypt_if_needed($encrypted_org3_name);
                                            $decrypted_org3_serve = decrypt_if_needed($encrypted_org3_serve);
                                            $decrypted_org3_date = decrypt_if_needed($encrypted_org3_date);
                                            $decrypted_marital_others = decrypt_if_needed($encrypted_marital_others);
                                            $decrypted_allowance_others = decrypt_if_needed($encrypted_allowance_others);
                                            $decrypted_residence_others = decrypt_if_needed($encrypted_residence_others);
                                            $decrypted_dose1_name = decrypt_if_needed($encrypted_dose1_name);
                                            $decrypted_dose1_date = decrypt_if_needed($encrypted_dose1_date);
                                            $decrypted_dose2_name = decrypt_if_needed($encrypted_dose2_name);
                                            $decrypted_dose2_date = decrypt_if_needed($encrypted_dose2_date);
                                            $decrypted_boost1_name = decrypt_if_needed($encrypted_boost1_name);
                                            $decrypted_boost1_date = decrypt_if_needed($encrypted_boost1_date);
                                            $decrypted_boost2_name = decrypt_if_needed($encrypted_boost2_name);
                                            $decrypted_boost2_date = decrypt_if_needed($encrypted_boost2_date);
                                            $decrypted_vaccination = decrypt_if_needed($encrypted_vaccination);
                                            $decrypted_vitamin = decrypt_if_needed($encrypted_vitamin);
                                            $decrypted_maintenance = decrypt_if_needed($encrypted_maintenance);
                                            $decrypted_vision = decrypt_if_needed($encrypted_vision);
                                            $decrypted_vision_specify = decrypt_if_needed($encrypted_vision_specify);
                                            $decrypted_hearing = decrypt_if_needed($encrypted_hearing);
                                            $decrypted_hearing_specify = decrypt_if_needed($encrypted_hearing_specify);
                                            $decrypted_speech = decrypt_if_needed($encrypted_speech);
                                            $decrypted_speech_specify = decrypt_if_needed($encrypted_speech_specify);
                                            $decrypted_genhealth = decrypt_if_needed($encrypted_genhealth);
                                            $decrypted_genhealth_specify = decrypt_if_needed($encrypted_genhealth_specify);
                                            $decrypted_health_con = decrypt_if_needed($encrypted_health_con);
                                            $decrypted_psychiatrist = decrypt_if_needed($encrypted_psychiatrist);
                                            $decrypted_psychiatrist_when = decrypt_if_needed($encrypted_psychiatrist_when);
                                            $decrypted_psychiatrist_what = decrypt_if_needed($encrypted_psychiatrist_what);
                                            $decrypted_psychologist = decrypt_if_needed($encrypted_psychologist);
                                            $decrypted_psychologist_when = decrypt_if_needed($encrypted_psychologist_when);
                                            $decrypted_psychologist_what = decrypt_if_needed($encrypted_psychologist_what);
                                            $decrypted_counselor = decrypt_if_needed($encrypted_counselor);
                                            $decrypted_counselor_when = decrypt_if_needed($encrypted_counselor_when);
                                            $decrypted_counselor_what = decrypt_if_needed($encrypted_counselor_what);
                                            $decrypted_fav_course = decrypt_if_needed($encrypted_fav_course);
                                            $decrypted_extra_others = decrypt_if_needed($encrypted_extra_others);
                                            $decrypted_pos_others = decrypt_if_needed($encrypted_pos_others);
                                            $decrypted_messenger = decrypt_if_needed($encrypted_messenger);
                                            $decrypted_viber = decrypt_if_needed($encrypted_viber);
                                            $decrypted_comm_email = decrypt_if_needed($encrypted_comm_email);
                                            $decrypted_comm_other = decrypt_if_needed($encrypted_comm_other);

                                        ?>

                                        <div class="row mb-1">
                                            <div class="col-sm-12 mb-2">
                                                <label for="address">Residential/Permanent Address</label>
                                                <input type="text" class="form-control" id="address" name="address" placeholder="House/Unit/Flr #. Bldg Name, Blk or Lot #, Barangay, City/Municipality, Province" value="<?php echo htmlspecialchars($decrypted_address); ?>">
                                            </div>
                                        </div>


                                        <div class="row mb-1">
                                            <div class="col-sm-3 mb-2">
                                                <label for="birthdate">Date of Birth</label>
                                                <input 
                                                    type="date" 
                                                    class="form-control" 
                                                    id="birthdate" 
                                                    name="birthdate" 
                                                    value="<?php echo htmlspecialchars($decrypted_birthdate); ?>"
                                                >
                                            </div>

                                            <script>
                                                // Function to set the max attribute dynamically
                                                function setMaxDate() {
                                                    const birthdateInput = document.getElementById('birthdate');
                                                    const today = new Date();
                                                    
                                                    // Calculate the max date as today minus 15 years
                                                    const maxDate = new Date();
                                                    maxDate.setFullYear(today.getFullYear() - 15);
                                                    
                                                    // Format maxDate to YYYY-MM-DD
                                                    const formattedMaxDate = maxDate.toISOString().split('T')[0];
                                                    birthdateInput.max = formattedMaxDate;
                                                }

                                                // Set the max date on page load
                                                document.addEventListener("DOMContentLoaded", setMaxDate);

                                                // Recalculate the max date every year (or anytime needed dynamically)
                                                setInterval(setMaxDate, 1000 * 60 * 60 * 24); // Adjust once per day
                                            </script>

                                            <div class="col-sm-4 mb-2">
                                                <label for="birthplace">Place of Birth</label>
                                                <input type="text" class="form-control" id="birthplace" name="birthplace" placeholder="Place of Birth" value="<?php echo htmlspecialchars($decrypted_birthplace); ?>">
                                            </div>
                                            <div class="col-sm-2 mb-2">
                                                <label for="gender">Sex</label>
                                                <div class="input-group">
                                                    <select class="form-control" id="gender" name="gender">
                                                        <option value="" disabled selected>Select</option>
                                                        <?php
                                                        $genders_query = "SELECT * FROM tbl_genders";
                                                        $genders_result = mysqli_query($conn, $genders_query);

                                                        while ($gender = mysqli_fetch_assoc($genders_result)) {
                                                            $selected = ($info_data['gender_id'] == $gender['gender_id']) ? 'selected' : '';
                                                            echo "<option value='{$gender['gender_id']}' $selected>{$gender['gender_name']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 mb-2">
                                            <label for="status">Civil Status</label>
                                                <div class="input-group">
                                                    <select class="form-control" id="status" name="status">
                                                        <option value="" disabled selected>Select Status</option>
                                                        <?php
                                                        $status_query = "SELECT * FROM tbl_statuses";
                                                        $status_result = mysqli_query($conn, $status_query);

                                                        while ($status = mysqli_fetch_assoc($status_result)) {
                                                            $selected = ($info_data['status_id'] == $status['status_id']) ? 'selected' : '';
                                                            echo "<option value='{$status['status_id']}' $selected>{$status['status_name']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
   
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-sm-5 mb-2">
                                                <label for="citizenship">Citizenship</label>
                                                <input type="text" class="form-control" id="citizenship" name="citizenship" placeholder="Citizenship" value="<?php echo htmlspecialchars($decrypted_citizenship); ?>">
                                            </div>
                                            <div class="col-sm-2 mb-2">
                                                <label for="height">Height (cm)</label>
                                                <input type="text" class="form-control" id="height" name="height" placeholder="Height" value="<?php echo htmlspecialchars($decrypted_height); ?>">
                                            </div>
                                            <div class="col-sm-2 mb-2">
                                                <label for="weight">Weight (kg)</label>
                                                <input type="text" class="form-control" id="weight" name="weight" placeholder="Weight" value="<?php echo htmlspecialchars($decrypted_weight); ?>">
                                            </div>
                                            <div class="col-sm-3 mb-2">
                                                <label for="bloodtype">Blood Type</label>
                                                <div class="input-group">
                                                <select class="form-control" id="blood_id" name="blood_id">
                                                    <option value="" disabled selected>Select Blood Type</option>
                                                    <?php
                                                    $bloodtypes_query = "SELECT * FROM tbl_bloodtypes";
                                                    $bloodtypes_result = mysqli_query($conn, $bloodtypes_query);

                                                    while ($blood_id = mysqli_fetch_assoc($bloodtypes_result)) {
                                                        $selected = ($info_data['blood_id'] == $blood_id['blood_id']) ? 'selected' : '';
                                                        echo "<option value='{$blood_id['blood_id']}' $selected>{$blood_id['blood_name']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-4">  
                                            <div class="col-sm-4 mb-2">
                                                <label for="mobile_no">Mobile Number</label>
                                                <input type="text" class="form-control" id="mobile_no" name="mobile_no" placeholder="Mobile Number" value="<?php echo htmlspecialchars($decrypted_mobile_no); ?>">
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <label for="tel_no">Telephone Number</label>
                                                <input type="text" class="form-control" id="tel_no" name="tel_no" placeholder="Telephone Number" value="<?php echo htmlspecialchars($decrypted_tel_no); ?>">
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <label for="email">Email</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($student_email); ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-1">
                                            <div class="col-sm-12 mb-2">
                                                <h5><b>II. Family Background</b></h5>
                                            </div>
                                            <div class="col-sm-6 mb-2">
                                                <label for="father_name">Father's Name</label>
                                                <input type="text" class="form-control" id="father_name" name="father_name" placeholder="Father's Name" value="<?php echo htmlspecialchars($decrypted_father_name); ?>">
                                            </div>
                                            <div class="col-sm-3 mb-2">
                                                <label for="father_occu">Occupation</label>
                                                <input type="text" class="form-control" id="father_occu" name="father_occu" placeholder="Occupation" value="<?php echo htmlspecialchars($decrypted_father_occu); ?>">
                                            </div>
                                            <div class="col-sm-3 mb-2">
                                                <label for="father_contact">Contact Number</label>
                                                <input type="text" class="form-control" id="father_contact" name="father_contact" placeholder="Contact Number" value="<?php echo htmlspecialchars($decrypted_father_contact); ?>">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-sm-6 mb-2">
                                                <label for="mother_name">Mother's Name</label>
                                                <input type="text" class="form-control" id="mother_name" name="mother_name" placeholder="Mother's Name" value="<?php echo htmlspecialchars($decrypted_mother_name); ?>">
                                            </div>
                                            <div class="col-sm-3 mb-2">
                                                <label for="mother_occu">Occupation</label>
                                                <input type="text" class="form-control" id="mother_occu" name="mother_occu" placeholder="Occupation" value="<?php echo htmlspecialchars($decrypted_mother_occu); ?>">
                                            </div>
                                            <div class="col-sm-3 mb-2">
                                                <label for="mother_contact">Contact Number</label>
                                                <input type="text" class="form-control" id="mother_contact" name="mother_contact" placeholder="Contact Number" value="<?php echo htmlspecialchars($decrypted_mother_contact); ?>">
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-sm-12 mb-2 text-center">
                                                <h5 style="font-size: 1.1em; text-align: center;"><b>Siblings/Children</b></h5>
                                                <p class="mb-1" style="font-style: italic;">(From First to Last Child/Sibling)</p>
                                            </div>

                                            <div class="col-sm-6 mb-2">
                                                <label for="sib1_name">Complete Name</label>
                                                <input type="text" class="form-control" id="sib1_name" name="sib1_name" placeholder="Complete Name" value="<?php echo htmlspecialchars($decrypted_sib1_name); ?>">
                                            </div>
                                            <div class="col-sm-3 mb-2">
                                                <label for="sib1_occu">Occupation</label>
                                                <input type="text" class="form-control" id="sib1_occu" name="sib1_occu" placeholder="Occupation" value="<?php echo htmlspecialchars($decrypted_sib1_occu); ?>">
                                            </div>
                                            <div class="col-sm-3 mb-2">
                                                <label for="sib1_contact">Contact Number</label>
                                                <input type="text" class="form-control" id="sib1_contact" name="sib1_contact" placeholder="Contact Number" value="<?php echo htmlspecialchars($decrypted_sib1_contact); ?>">
                                            </div>

                                            <?php for ($i = 2; $i <= 7; $i++): ?>
                                                <div class="col-sm-6 mb-2">
                                                    <input type="text" class="form-control" id="sib<?php echo $i; ?>_name" name="sib<?php echo $i; ?>_name" placeholder="Complete Name" value="<?php echo htmlspecialchars(${"decrypted_sib{$i}_name"}); ?>">
                                                </div>
                                                <div class="col-sm-3 mb-2">
                                                    <input type="text" class="form-control" id="sib<?php echo $i; ?>_occu" name="sib<?php echo $i; ?>_occu" placeholder="Occupation" value="<?php echo htmlspecialchars(${"decrypted_sib{$i}_occu"}); ?>">
                                                </div>
                                                <div class="col-sm-3 mb-2">
                                                    <input type="text" class="form-control" id="sib<?php echo $i; ?>_contact" name="sib<?php echo $i; ?>_contact" placeholder="Contact Number" value="<?php echo htmlspecialchars(${"decrypted_sib{$i}_contact"}); ?>">
                                                </div>
                                            <?php endfor; ?>
                                        </div>
                                        
                                        <div class="row mb-1">
                                            <div class="col-sm-12 mb-2">
                                                <h5><b>III. Educational Background</b></h5>
                                            </div>
                                            <div class="col-sm-8 mb-2">
                                                <label for="gs_name">Grade School</label>
                                                <input type="text" class="form-control" id="gs_name" name="gs_name" placeholder="Name of School" value="<?php echo htmlspecialchars($decrypted_gs_name); ?>">
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <label for="gs_type">Type of School</label>
                                                <select class="form-control" id="gs_type" name="gs_type">
                                                    <option value="" disabled selected>Select</option>
                                                    <option value="Public" <?php echo (isset($decrypted_gs_type) && $decrypted_gs_type == 'Public') ? 'selected' : ''; ?>>Public</option>
                                                    <option value="Private" <?php echo (isset($decrypted_gs_type) && $decrypted_gs_type == 'Private') ? 'selected' : ''; ?>>Private</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <div class="col-sm-2 offset-sm-6">
                                                <label for="gs_year">Year </label>
                                                <input type="number" min="2000" max="2025" class="form-control" id="gs_year" name="gs_year" placeholder="Year Graduated" value="<?php echo htmlspecialchars($decrypted_gs_year); ?>">
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <label for="gs_honor">Honors Received</label>
                                                <input type="text" class="form-control" id="gs_honor" name="gs_honor" placeholder="Honors Received" value="<?php echo htmlspecialchars($decrypted_gs_honor); ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-1">
                                            <div class="col-sm-8 mb-2">
                                                <label for="jhs_name">Junior High School</label>
                                                <input type="text" class="form-control" id="jhs_name" name="jhs_name" placeholder="Name of School" value="<?php echo htmlspecialchars($decrypted_jhs_name); ?>">
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <label for="jhs_type">Type of School</label>
                                                <select class="form-control" id="jhs_type" name="jhs_type">
                                                    <option value="" disabled selected>Select</option>
                                                    <option value="Public" <?php echo (isset($decrypted_jhs_type) && $decrypted_jhs_type == 'Public') ? 'selected' : ''; ?>>Public</option>
                                                    <option value="Private" <?php echo (isset($decrypted_jhs_type) && $decrypted_jhs_type == 'Private') ? 'selected' : ''; ?>>Private</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <div class="col-sm-2 offset-sm-6">
                                                <label for="jhs_year">Year </label>
                                                <input type="number" min="2000" max="2025" class="form-control" id="jhs_year" name="jhs_year" placeholder="Year Graduated" value="<?php echo htmlspecialchars($decrypted_jhs_year); ?>">
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <label for="jhs_honor">Honors Received</label>
                                                <input type="text" class="form-control" id="jhs_honor" name="jhs_honor" placeholder="Honors Received" value="<?php echo htmlspecialchars($decrypted_jhs_honor); ?>">
                                            </div>
                                        </div>

                                        <div class="row mb-1">
                                            <div class="col-sm-8 mb-2">
                                                <label for="shs_name">Senior High School</label>
                                                <input type="text" class="form-control" id="shs_name" name="shs_name" placeholder="Name of School" value="<?php echo htmlspecialchars($decrypted_shs_name); ?>">
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <label for="shs_type">Type of School</label>
                                                <select class="form-control" id="shs_type" name="shs_type">
                                                    <option value="" disabled selected>Select</option>
                                                    <option value="Public" <?php echo (isset($decrypted_shs_type) && $decrypted_shs_type == 'Public') ? 'selected' : ''; ?>>Public</option>
                                                    <option value="Private" <?php echo (isset($decrypted_shs_type) && $decrypted_shs_type == 'Private') ? 'selected' : ''; ?>>Private</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <div class="col-sm-2 offset-sm-6">
                                                <label for="shs_year">Year </label>
                                                <input type="number" min="2000" max="2025" class="form-control" id="shs_year" name="shs_year" placeholder="Year Graduated" value="<?php echo htmlspecialchars($decrypted_shs_year); ?>">
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <label for="shs_honor">Honors Received</label>
                                                <input type="text" class="form-control" id="shs_honor" name="shs_honor" placeholder="Honors Received" value="<?php echo htmlspecialchars($decrypted_shs_honor); ?>">
                                            </div>
                                        </div>

                                        <div class="row mb-1">
                                            <div class="col-sm-8 mb-2">
                                                <label for="vs_name">Vocational School</label>
                                                <input type="text" class="form-control" id="vs_name" name="vs_name" placeholder="Name of School" value="<?php echo htmlspecialchars($decrypted_vs_name); ?>">
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <label for="vs_type">Type of School</label>
                                                <select class="form-control" id="vs_type" name="vs_type">
                                                    <option value="" disabled selected>Select</option>
                                                    <option value="Public" <?php echo (isset($decrypted_vs_type) && $decrypted_vs_type == 'Public') ? 'selected' : ''; ?>>Public</option>
                                                    <option value="Private" <?php echo (isset($decrypted_vs_type) && $decrypted_vs_type == 'Private') ? 'selected' : ''; ?>>Private</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <div class="col-sm-2 offset-sm-6">
                                                <label for="vs_year">Year </label>
                                                <input type="number" min="2000" max="2025" class="form-control" id="vs_year" name="vs_year" placeholder="Year Graduated" value="<?php echo htmlspecialchars($decrypted_vs_year); ?>">
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <label for="vs_honor">Honors Received</label>
                                                <input type="text" class="form-control" id="vs_honor" name="vs_honor" placeholder="Honors Received" value="<?php echo htmlspecialchars($decrypted_vs_honor); ?>">
                                            </div>
                                        </div>

                                        <div class="row mb-1">
                                            <div class="col-sm-8 mb-2">
                                                <label for="college_name">Undergraduate/ College</label>
                                                <input type="text" class="form-control" id="college_name" name="college_name" placeholder="Name of School" value="<?php echo htmlspecialchars($decrypted_college_name); ?>">
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <label for="college_type">Type of School</label>
                                                <select class="form-control" id="college_type" name="college_type">
                                                    <option value="" disabled selected>Select</option>
                                                    <option value="Public" <?php echo (isset($decrypted_college_type) && $decrypted_college_type == 'Public') ? 'selected' : ''; ?>>Public</option>
                                                    <option value="Private" <?php echo (isset($decrypted_college_type) && $decrypted_college_type == 'Private') ? 'selected' : ''; ?>>Private</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <div class="col-sm-2 offset-sm-6">
                                                <label for="college_year">Year </label>
                                                <input type="number" min="2000" max="2025" class="form-control" id="college_year" name="college_year" placeholder="Year Graduated" value="<?php echo htmlspecialchars($decrypted_college_year); ?>">
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <label for="college_honor">Honors Received</label>
                                                <input type="text" class="form-control" id="college_honor" name="college_honor" placeholder="Honors Received" value="<?php echo htmlspecialchars($decrypted_college_honor); ?>">
                                            </div>
                                        </div>

                                        <div class="row mb-1">
                                            <div class="col-sm-12 mb-2">
                                                <h5><b>IV. Voluntary Work/ Athletical Affiliation</b></h5>
                                            </div>
                                            <div class="col-sm-5 mb-2">
                                                <label for="org1_name">Organization/ Athletics</label>
                                                <input type="text" class="form-control" id="org1_name" name="org1_name" placeholder="Organization Name" value="<?php echo htmlspecialchars($decrypted_org1_name); ?>">
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <label for="org1_serve">Service/s Rendered</label>
                                                <input type="text" class="form-control" id="org1_serve" name="org1_serve" placeholder="Service Name" value="<?php echo htmlspecialchars($decrypted_org1_serve); ?>">
                                            </div>
                                            <div class="col-sm-3 mb-2">
                                                <label for="org1_date">Date</label>
                                                <input type="date" class="form-control" id="org1_date" name="org1_date" value="<?php echo htmlspecialchars($decrypted_org1_date); ?>">
                                            </div>
                                        </div>

                                        <div class="row mb-1">
                                            <div class="col-sm-5 mb-2">
                                                <input type="text" class="form-control" id="org2_name" name="org2_name" placeholder="Organization Name" value="<?php echo htmlspecialchars($decrypted_org2_name); ?>">
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <input type="text" class="form-control" id="org2_serve" name="org2_serve" placeholder="Service Name" value="<?php echo htmlspecialchars($decrypted_org2_serve); ?>">
                                            </div>
                                            <div class="col-sm-3 mb-2">
                                                <input type="date" class="form-control" id="org2_date" name="org2_date" value="<?php echo htmlspecialchars($decrypted_org2_date); ?>">
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-sm-5 mb-2">
                                                <input type="text" class="form-control" id="org3_name" name="org3_name" placeholder="Organization Name" value="<?php echo htmlspecialchars($decrypted_org3_name); ?>">
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <input type="text" class="form-control" id="org3_serve" name="org3_serve" placeholder="Service Name" value="<?php echo htmlspecialchars($decrypted_org3_serve); ?>">
                                            </div>
                                            <div class="col-sm-3 mb-2">
                                                <input type="date" class="form-control" id="org3_date" name="org3_date" value="<?php echo htmlspecialchars($decrypted_org3_date); ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-4">
                                            <div class="col-sm-12 mb-2">
                                                <h5><b>V. Student’s Life Information</b></h5>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group row mb-2">
                                                    <label for="marital_id" class="col-sm-4 offset-sm-1 col-form-label">1. Parent’s Marital Status</label>
                                                    <div class="col-sm-4">
                                                        <select class="form-control" id="marital_id" name="marital_id" onchange="toggleSpecifyBox('marital_id', 'marital_others', '5')">
                                                            <option value="" disabled selected>Select</option>
                                                            <?php
                                                            // Fetch options from the database
                                                            $marital_query = "SELECT * FROM tbl_marital";
                                                            $marital_result = mysqli_query($conn, $marital_query);

                                                            while ($marital = mysqli_fetch_assoc($marital_result)) {
                                                                $selected = ($info_data['marital_id'] == $marital['marital_id']) ? 'selected' : '';
                                                                echo "<option value='{$marital['marital_id']}' $selected>{$marital['marital_name']}</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <input type="text" class="form-control" id="marital_others" name="marital_others" placeholder="Please specify" value="<?php echo htmlspecialchars($decrypted_marital_others); ?>" disabled>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                <div class="form-group row mb-2">
                                                    <label for="finance_id" class="col-sm-4 offset-sm-1 col-form-label">2. Who finances your schooling?</label>
                                                    <div class="col-sm-4">
                                                    <select class="form-control" id="finance_id" name="finance_id">
                                                        <option value="" disabled selected>Select</option>
                                                        <?php
                                                        $finance_query = "SELECT * FROM tbl_finances";
                                                        $finance_result = mysqli_query($conn, $finance_query);

                                                        while ($finance = mysqli_fetch_assoc($finance_result)) {
                                                            $selected = ($info_data['finance_id'] == $finance['finance_id']) ? 'selected' : '';
                                                            echo "<option value='{$finance['finance_id']}' $selected>{$finance['finance_name']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-12">
                                                <div class="form-group row mb-2">
                                                    <label for="allowance_id" class="col-sm-4 offset-sm-1 col-form-label">3. How much is your daily allowance?</label>
                                                    <div class="col-sm-4">
                                                        <select class="form-control" id="allowance_id" name="allowance_id" onchange="toggleSpecifyBox('allowance_id', 'allowance_others', '6')">
                                                            <option value="" disabled selected>Select</option>
                                                            <?php
                                                            // Fetch options from the database
                                                            $allowance_query = "SELECT * FROM tbl_allowance";
                                                            $allowance_result = mysqli_query($conn, $allowance_query);

                                                            while ($allowance = mysqli_fetch_assoc($allowance_result)) {
                                                                $selected = ($info_data['allowance_id'] == $allowance['allowance_id']) ? 'selected' : '';
                                                                echo "<option value='{$allowance['allowance_id']}' $selected>{$allowance['allowance_name']}</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <input type="text" class="form-control" id="allowance_others" name="allowance_others" placeholder="Please specify" value="<?php echo htmlspecialchars($decrypted_allowance_others); ?>" disabled>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                <div class="form-group row mb-2">
                                                    <label for="income_id" class="col-sm-4 offset-sm-1 col-form-label">
                                                        4. Family Income
                                                        <br><small class="form-text text-muted"><em>(Total of those who are employed/with business)</em></small>
                                                    </label>
                                                    <div class="col-sm-4">
                                                        <select class="form-control" id="income_id" name="income_id">
                                                            <option value="" disabled selected>Select</option>
                                                            <?php
                                                            $income_query = "SELECT * FROM tbl_income";
                                                            $income_result = mysqli_query($conn, $income_query);

                                                            while ($income = mysqli_fetch_assoc($income_result)) {
                                                                $selected = ($info_data['income_id'] == $income['income_id']) ? 'selected' : '';
                                                                echo "<option value='{$income['income_id']}' $selected>{$income['income_name']}</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                <div class="form-group row mb-2">
                                                    <label for="residence_id" class="col-sm-4 offset-sm-1 col-form-label">5. Nature of Residence</label>
                                                    <div class="col-sm-4">
                                                        <select class="form-control" id="residence_id" name="residence_id" onchange="toggleSpecifyBox('residence_id', 'residence_others', '6')">
                                                            <option value="" disabled selected>Select</option>
                                                            <?php
                                                            // Fetch options from the database
                                                            $residence_query = "SELECT * FROM tbl_residence";
                                                            $residence_result = mysqli_query($conn, $residence_query);

                                                            while ($residence = mysqli_fetch_assoc($residence_result)) {
                                                                $selected = ($info_data['residence_id'] == $residence['residence_id']) ? 'selected' : '';
                                                                echo "<option value='{$residence['residence_id']}' $selected>{$residence['residence_name']}</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <input type="text" class="form-control" id="residence_others" name="residence_others" placeholder="Please specify" value="<?php echo htmlspecialchars($decrypted_residence_others); ?>" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <script>
                                                // Function to handle the specify field based on select input selection
                                                function toggleSpecifyBox(selectId, othersInputId, othersValue) {
                                                    const selectElement = document.getElementById(selectId);
                                                    const othersInput = document.getElementById(othersInputId);
                                                    
                                                    // Check if the "Others" option is selected
                                                    if (selectElement.value === othersValue) {
                                                        othersInput.disabled = false; // Enable the specify input
                                                        othersInput.setAttribute('required', 'required'); // Set the input as required
                                                    } else {
                                                        othersInput.disabled = true; // Disable if any other option
                                                        othersInput.value = ''; // Clear the input if it was previously filled
                                                        othersInput.removeAttribute('required'); // Remove the required attribute
                                                    }
                                                }

                                                // Initialize event listeners for Parent’s Marital Status
                                                document.getElementById('marital_id').addEventListener('change', () => {
                                                    toggleSpecifyBox('marital_id', 'marital_others', '5'); // Assuming '5' is the value for "Others"
                                                });

                                                // Initialize event listeners for Daily Allowance
                                                document.getElementById('allowance_id').addEventListener('change', () => {
                                                    toggleSpecifyBox('allowance_id', 'allowance_others', '6'); // Assuming '6' is the value for "Others"
                                                });

                                                // Initialize event listeners for Nature of Residence
                                                document.getElementById('residence_id').addEventListener('change', () => {
                                                    toggleSpecifyBox('residence_id', 'residence_others', '6'); // Assuming '6' is the value for "Others"
                                                });

                                                // Call the toggleSpecifyBox on page load for default state
                                                toggleSpecifyBox('marital_id', 'marital_others', '5');
                                                toggleSpecifyBox('allowance_id', 'allowance_others', '6');
                                                toggleSpecifyBox('residence_id', 'residence_others', '6');
                                            </script>

                                        </div>
                                        
                                        <div class="row mb-4">
                                            <div class="col-sm-12 mb-2">
                                                <h5><b>VI. Health Information</b></h5>
                                                <p style="margin-left: 60px; font-size: large;">A. Physical</p>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group row mb-2">
                                                    <label for="1st Dose" class="col-sm-2 offset-sm-1 col-form-label">1st Dose</label>
                                                    <div class="col-sm-5 mb-2">
                                                        <input type="text" class="form-control" id="dose1_name" name="dose1_name" placeholder="Brand Name" value="<?php echo htmlspecialchars($decrypted_dose1_name); ?>">
                                                    </div>
                                                    
                                                    <div class="col-sm-3 mb-2">
                                                        <input type="date" class="form-control" id="dose1_date" name="dose1_date" value="<?php echo htmlspecialchars($decrypted_dose1_date); ?>">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                <div class="form-group row mb-2">
                                                    <label for="2nd Dose" class="col-sm-2 offset-sm-1 col-form-label">2nd Dose</label>
                                                    <div class="col-sm-5 mb-2">
                                                        <input type="text" class="form-control" id="dose2_name" name="dose2_name" placeholder="Brand Name" value="<?php echo htmlspecialchars($decrypted_dose2_name); ?>">
                                                    </div>
                                                    
                                                    <div class="col-sm-3 mb-2">
                                                        <input type="date" class="form-control" id="dose2_date" name="dose2_date" value="<?php echo htmlspecialchars($decrypted_dose2_date); ?>">
                                                    </div>
                                                </div>
                                            </div>  

                                            <div class="col-sm-12">
                                                <div class="form-group row mb-2">
                                                    <label for="Booster 1" class="col-sm-2 offset-sm-1 col-form-label">Booster 1</label>
                                                    <div class="col-sm-5 mb-2">
                                                        <input type="text" class="form-control" id="boost1_name" name="boost1_name" placeholder="Brand Name" value="<?php echo htmlspecialchars($decrypted_boost1_name); ?>">
                                                    </div>
                                                    
                                                    <div class="col-sm-3 mb-2">
                                                        <input type="date" class="form-control" id="boost1_date" name="boost1_date" value="<?php echo htmlspecialchars($decrypted_boost1_date); ?>">
                                                    </div>
                                                </div>
                                            </div> 

                                            <div class="col-sm-12">
                                                <div class="form-group row mb-2">
                                                    <label for="Booster 2" class="col-sm-2 offset-sm-1 col-form-label">Booster 2</label>
                                                    <div class="col-sm-5 mb-2">
                                                        <input type="text" class="form-control" id="boost2_name" name="boost2_name" placeholder="Brand Name" value="<?php echo htmlspecialchars($decrypted_boost2_name); ?>">
                                                    </div>
                                                    
                                                    <div class="col-sm-3 mb-2">
                                                        <input type="date" class="form-control" id="boost2_date" name="boost2_date" value="<?php echo htmlspecialchars($decrypted_boost2_date); ?>">
                                                    </div>
                                                </div>
                                            </div> 

                                            <div class="col-sm-12">
                                                <div class="form-group row mb-2">
                                                    <label for="Vaccination" class="col-sm-4 offset-sm-1 col-form-label">Other Vaccination:</label>
                                                    <div class="col-sm-6 mb-2">
                                                        <input type="text" class="form-control" id="vaccination" name="vaccination" placeholder="Brand Name" value="<?php echo htmlspecialchars($decrypted_vaccination); ?>">
                                                    </div>
                                                </div>
                                            </div> 

                                            <div class="col-sm-12">
                                                <div class="form-group row mb-2">
                                                    <label for="Vitamins/Supplements" class="col-sm-4 offset-sm-1 col-form-label">Vitamins/Supplements:</label>
                                                    <div class="col-sm-6 mb-2">
                                                        <input type="text" class="form-control" id="vitamin" name="vitamin" placeholder="Brand Name" value="<?php echo htmlspecialchars($decrypted_vitamin); ?>">
                                                    </div>
                                                </div>
                                            </div> 

                                            <div class="col-sm-12">
                                                <div class="form-group row mb-2">
                                                    <label for="Maintenance" class="col-sm-4 offset-sm-1 col-form-label">Maintenance:</label>
                                                    <div class="col-sm-6 mb-2">
                                                        <input type="text" class="form-control" id="maintenance" name="maintenance" placeholder="Brand Name" value="<?php echo htmlspecialchars($decrypted_maintenance); ?>">
                                                    </div>
                                                </div>
                                            </div> 

                                            <div class="col-sm-12 mb-2">
                                                <label for="vision" class="col-sm-20 offset-sm-1 col-form-label" style="font-size: 18px;">Do you have problems with your…? (Please Check)</label>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group row mb-2">
                                                    <label for="vision" class="col-sm-2 offset-sm-1 col-form-label">Vision:</label>
                                                    <div class="col-sm-2 mb-2 align-items-center">
                                                        <div class="form-check me-1">
                                                            <input type="radio" class="form-check-input" id="vision_yes" name="vision" value="yes" <?php echo (isset($info_data['vision']) && $info_data['vision'] === 'yes') ? 'checked' : ($decrypted_vision === 'yes' ? 'checked' : ''); ?>>
                                                            <label class="form-check-label" for="vision_yes">Yes</label>
                                                        </div>
                                                        <div class="form-check me-1">
                                                            <input type="radio" class="form-check-input" id="vision_no" name="vision" value="no" <?php echo (isset($info_data['vision']) && $info_data['vision'] === 'no') ? 'checked' : ($decrypted_vision === 'no' ? 'checked' : ''); ?>>
                                                            <label class="form-check-label" for="vision_no">No</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4 mb-2">
                                                        <input type="text" class="form-control" id="vision_specify" name="vision_specify" placeholder="If Yes, please specify:" value="<?php echo htmlspecialchars($decrypted_vision_specify); ?>"disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <script>
                                                // Function to handle the Vision radio buttons and "specify" input
                                                function handleVisionChange() {
                                                    const yesRadio = document.getElementById('vision_yes');
                                                    const specifyInput = document.getElementById('vision_specify');

                                                    if (yesRadio.checked) {
                                                        specifyInput.disabled = false;  // Enable the specify input
                                                        specifyInput.required = true;   // Make it required
                                                    } else {
                                                        specifyInput.disabled = true;   // Disable the specify input
                                                        specifyInput.value = '';        // Clear the input field
                                                        specifyInput.required = false;  // Make it not required
                                                    }
                                                }

                                                // Add event listeners to Vision radio buttons
                                                document.getElementById('vision_yes').addEventListener('change', handleVisionChange);
                                                document.getElementById('vision_no').addEventListener('change', handleVisionChange);

                                                // Call handleVisionChange on page load for the default state
                                                handleVisionChange();
                                            </script>

                                            <div class="col-sm-12">
                                                <div class="form-group row mb-2">
                                                    <label for="hearing" class="col-sm-2 offset-sm-1 col-form-label">Hearing:</label>
                                                    <div class="col-sm-2 mb-2 align-items-center">
                                                        <div class="form-check me-1">
                                                            <input type="radio" class="form-check-input" id="hearing_yes" name="hearing" value="yes" <?php echo (isset($info_data['hearing']) && $info_data['hearing'] === 'yes') ? 'checked' : ($decrypted_hearing === 'yes' ? 'checked' : ''); ?>>
                                                            <label class="form-check-label" for="hearing_yes">Yes</label>
                                                        </div>
                                                        <div class="form-check me-1">
                                                            <input type="radio" class="form-check-input" id="hearing_no" name="hearing" value="no" <?php echo (isset($info_data['hearing']) && $info_data['hearing'] === 'no') ? 'checked' : ($decrypted_hearing === 'no' ? 'checked' : ''); ?>>
                                                            <label class="form-check-label" for="hearing_no">No</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4 mb-2">
                                                        <input type="text" class="form-control" id="hearing_specify" name="hearing_specify" placeholder="If Yes, please specify:" value="<?php echo htmlspecialchars($decrypted_hearing_specify); ?>"disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <script>
                                                // Function to handle the Vision radio buttons and "specify" input
                                                function handleHearingChange() {
                                                    const yesRadio = document.getElementById('hearing_yes');
                                                    const specifyInput = document.getElementById('hearing_specify');

                                                    if (yesRadio.checked) {
                                                        specifyInput.disabled = false;  // Enable the specify input
                                                        specifyInput.required = true;   // Make it required
                                                    } else {
                                                        specifyInput.disabled = true;   // Disable the specify input
                                                        specifyInput.value = '';        // Clear the input field
                                                        specifyInput.required = false;  // Make it not required
                                                    }
                                                }

                                                // Add event listeners to Hearing radio buttons
                                                document.getElementById('hearing_yes').addEventListener('change', handleHearingChange);
                                                document.getElementById('hearing_no').addEventListener('change', handleHearingChange);

                                                // Call handleHearingChange on page load for the default state
                                                handleHearingChange();
                                            </script>

                                            <div class="col-sm-12">
                                                <div class="form-group row mb-2">
                                                    <label for="speech" class="col-sm-2 offset-sm-1 col-form-label">Speech:</label>
                                                    <div class="col-sm-2 mb-2 align-items-center">
                                                        <div class="form-check me-1">
                                                            <input type="radio" class="form-check-input" id="speech_yes" name="speech" value="yes" <?php echo (isset($info_data['speech']) && $info_data['speech'] === 'yes') ? 'checked' : ($decrypted_speech === 'yes' ? 'checked' : ''); ?>>
                                                            <label class="form-check-label" for="speech_yes">Yes</label>
                                                        </div>
                                                        <div class="form-check me-1">
                                                            <input type="radio" class="form-check-input" id="speech_no" name="speech" value="no" <?php echo (isset($info_data['speech']) && $info_data['speech'] === 'no') ? 'checked' : ($decrypted_speech === 'no' ? 'checked' : ''); ?>>
                                                            <label class="form-check-label" for="speech_no">No</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4 mb-2">
                                                        <input type="text" class="form-control" id="speech_specify" name="speech_specify" placeholder="If Yes, please specify:" value="<?php echo htmlspecialchars($decrypted_speech_specify); ?>"disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <script>
                                                // Function to handle the Vision radio buttons and "specify" input
                                                function handleSpeechChange() {
                                                    const yesRadio = document.getElementById('speech_yes');
                                                    const specifyInput = document.getElementById('speech_specify');

                                                    if (yesRadio.checked) {
                                                        specifyInput.disabled = false;  // Enable the specify input
                                                        specifyInput.required = true;   // Make it required
                                                    } else {
                                                        specifyInput.disabled = true;   // Disable the specify input
                                                        specifyInput.value = '';        // Clear the input field
                                                        specifyInput.required = false;  // Make it not required
                                                    }
                                                }

                                                // Add event listeners to Speech radio buttons
                                                document.getElementById('speech_yes').addEventListener('change', handleSpeechChange);
                                                document.getElementById('speech_no').addEventListener('change', handleSpeechChange);

                                                // Call handleSpeechChange on page load for the default state
                                                handleSpeechChange();
                                            </script>

                                            <div class="col-sm-12">
                                                <div class="form-group row mb-2">
                                                    <label for="genhealth" class="col-sm-2 offset-sm-1 col-form-label">General Health:</label>
                                                    <div class="col-sm-2 mb-2 align-items-center">
                                                        <div class="form-check me-1">
                                                            <input type="radio" class="form-check-input" id="genhealth_yes" name="genhealth" value="yes" <?php echo (isset($info_data['genhealth']) && $info_data['genhealth'] === 'yes') ? 'checked' : ($decrypted_genhealth === 'yes' ? 'checked' : ''); ?>>
                                                            <label class="form-check-label" for="genhealth_yes">Yes</label>
                                                        </div>
                                                        <div class="form-check me-1">
                                                            <input type="radio" class="form-check-input" id="genhealth_no" name="genhealth" value="no" <?php echo (isset($info_data['genhealth']) && $info_data['genhealth'] === 'no') ? 'checked' : ($decrypted_genhealth === 'no' ? 'checked' : ''); ?>>
                                                            <label class="form-check-label" for="genhealth_no">No</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4 mb-2">
                                                        <input type="text" class="form-control" id="genhealth_specify" name="genhealth_specify" placeholder="If Yes, please specify:" value="<?php echo htmlspecialchars($decrypted_genhealth_specify); ?>"disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <script>
                                                // Function to handle the Vision radio buttons and "specify" input
                                                function handleGenhealthChange() {
                                                    const yesRadio = document.getElementById('genhealth_yes');
                                                    const specifyInput = document.getElementById('genhealth_specify');

                                                    if (yesRadio.checked) {
                                                        specifyInput.disabled = false;  // Enable the specify input
                                                        specifyInput.required = true;   // Make it required
                                                    } else {
                                                        specifyInput.disabled = true;   // Disable the specify input
                                                        specifyInput.value = '';        // Clear the input field
                                                        specifyInput.required = false;  // Make it not required
                                                    }
                                                }

                                                // Add event listeners to Genhealth radio buttons
                                                document.getElementById('genhealth_yes').addEventListener('change', handleGenhealthChange);
                                                document.getElementById('genhealth_no').addEventListener('change', handleGenhealthChange);

                                                // Call handleGenhealthChange on page load for the default state
                                                handleGenhealthChange();
                                            </script>

                                            <div class="col-sm-12 mb-2">
                                                <label for="health_con" class="col-sm-20 offset-sm-1 col-form-label" style="font-size: 17px;">Do you have any pre-existing health condition?</label>
                                                <p style="margin-left: 110px; font-size: medium;">(Asthma, Hypertension, Diabetes, etc.)</p>
                                                <div class="form-group row mb-2 class=col-sm-2 offset-sm-1">
                                                    <div class="col-sm-6 mb-2">
                                                        <input type="text" class="form-control" id="health_con" name="health_con" placeholder="If you have, please specify:" value="<?php echo htmlspecialchars($decrypted_health_con); ?>">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-12 mb-2">
                                                <p style="margin-left: 60px; font-size: large;">B. Socio-Psychological</p>
                                                <p style="margin-left: 110px; font-size: medium;">Previous Consultation</p>
                                            </div>
                                            <div class="col-sm-12 offset-sm-1">
                                                <label for="Consulted" class="form-label">Consulted</label><label for="When" class="col-sm-2 offset-sm-2-5 form-label">When</label><label for="what" class="col-sm-2 offset-sm-1-1 form-label">For What?</label>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group row mb-2">
                                                    <label for="Psychiatrist" class="col-sm-2 offset-sm-1 col-form-label">Psychiatrist</label>
                                                    <div class="mb-2 align-items-center">
                                                        <div class="form-check me-1">
                                                            <input type="radio" class="form-check-input" id="psychiatrist_yes" name="psychiatrist" value="yes" <?php echo (isset($decrypted_psychiatrist) && $decrypted_psychiatrist === 'yes') ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="psychiatrist_yes">Yes</label>
                                                        </div>
                                                        <div class="form-check me-1">
                                                            <input type="radio" class="form-check-input" id="psychiatrist_no" name="psychiatrist" value="no" <?php echo (isset($decrypted_psychiatrist) && $decrypted_psychiatrist === 'no') ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="psychiatrist_no">No</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 offset-sm-1 mb-2">
                                                        <input type="date" class="form-control" id="psychiatrist_when" name="psychiatrist_when" value="<?php echo htmlspecialchars($decrypted_psychiatrist_when); ?>" disabled>
                                                    </div>
                                                    <div class="col-sm-4 mb-2">
                                                        <input type="text" class="form-control" id="psychiatrist_what" name="psychiatrist_what" placeholder="Description" value="<?php echo htmlspecialchars($decrypted_psychiatrist_what); ?>" disabled>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                <div class="form-group row mb-2">
                                                    <label for="Psychologist" class="col-sm-2 offset-sm-1 col-form-label">Psychologist</label>
                                                    <div class="mb-2 align-items-center">
                                                        <div class="form-check me-1">
                                                            <input type="radio" class="form-check-input" id="psychologist_yes" name="psychologist" value="yes" <?php echo (isset($decrypted_psychologist) && $decrypted_psychologist === 'yes') ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="psychologist_yes">Yes</label>
                                                        </div>
                                                        <div class="form-check me-1">
                                                            <input type="radio" class="form-check-input" id="psychologist_no" name="psychologist" value="no" <?php echo (isset($decrypted_psychologist) && $decrypted_psychologist === 'no') ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="psychologist_no">No</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 offset-sm-1 mb-2">
                                                        <input type="date" class="form-control" id="psychologist_when" name="psychologist_when" value="<?php echo htmlspecialchars($decrypted_psychologist_when); ?>" disabled>
                                                    </div>
                                                    <div class="col-sm-4 mb-2">
                                                        <input type="text" class="form-control" id="psychologist_what" name="psychologist_what" placeholder="Description" value="<?php echo htmlspecialchars($decrypted_psychologist_what); ?>" disabled>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                <div class="form-group row mb-2">
                                                    <label for="Guidance Counselor" class="col-sm-2 offset-sm-1 col-form-label">Guidance Counselor</label>
                                                    <div class="mb-2 align-items-center">
                                                        <div class="form-check me-1">
                                                            <input type="radio" class="form-check-input" id="counselor_yes" name="counselor" value="yes" <?php echo (isset($decrypted_counselor) && $decrypted_counselor === 'yes') ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="counselor_yes">Yes</label>
                                                        </div>
                                                        <div class="form-check me-1">
                                                            <input type="radio" class="form-check-input" id="counselor_no" name="counselor" value="no" <?php echo (isset($decrypted_counselor) && $decrypted_counselor === 'no') ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="counselor_no">No</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 offset-sm-1 mb-2">
                                                        <input type="date" class="form-control" id="counselor_when" name="counselor_when" value="<?php echo htmlspecialchars($decrypted_counselor_when); ?>" disabled>
                                                    </div>
                                                    <div class="col-sm-4 mb-2">
                                                        <input type="text" class="form-control" id="counselor_what" name="counselor_what" placeholder="Description" value="<?php echo htmlspecialchars($decrypted_counselor_what); ?>" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <script>
                                                // Function to handle the fields based on radio button selection
                                                function handleInputChange(sectionPrefix) {
                                                    const yesRadio = document.getElementById(`${sectionPrefix}_yes`);
                                                    const noRadio = document.getElementById(`${sectionPrefix}_no`);
                                                    const whenInput = document.getElementById(`${sectionPrefix}_when`);
                                                    const whatInput = document.getElementById(`${sectionPrefix}_what`);

                                                    if (yesRadio.checked) {
                                                        whenInput.disabled = false; // Enable "When" input if Yes is checked
                                                        whatInput.disabled = false; // Enable "What" input if Yes is checked
                                                        whenInput.required = true;   // Make "When" required
                                                        whatInput.required = true;    // Make "What" required
                                                    } else {
                                                        whenInput.disabled = true;    // Keep "When" input disabled if No is checked
                                                        whatInput.disabled = true;     // Keep "What" input disabled if No is checked
                                                        whenInput.value = '';          // Clear the "When" input if it was previously filled
                                                        whatInput.value = '';          // Clear the "What" input if it was previously filled
                                                        whenInput.required = false;    // Make "When" not required
                                                        whatInput.required = false;     // Make "What" not required
                                                    }
                                                }

                                                // Initialize event listeners for each section
                                                document.getElementById('psychiatrist_yes').addEventListener('change', () => handleInputChange('psychiatrist'));
                                                document.getElementById('psychiatrist_no').addEventListener('change', () => handleInputChange('psychiatrist'));

                                                document.getElementById('psychologist_yes').addEventListener('change', () => handleInputChange('psychologist'));
                                                document.getElementById('psychologist_no').addEventListener('change', () => handleInputChange('psychologist'));

                                                document.getElementById('counselor_yes').addEventListener('change', () => handleInputChange('counselor'));
                                                document.getElementById('counselor_no').addEventListener('change', () => handleInputChange('counselor'));

                                                // Call handleInputChange on page load for default state
                                                handleInputChange('psychiatrist');
                                                handleInputChange('psychologist');
                                                handleInputChange('counselor');
                                            </script>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-sm-12 mb-2">
                                                <h5><b>VII. Interest and Hobbies</b></h5>
                                            </div>

                                            <div class="col-sm-12">
                                                <div class="form-group row mb-2">
                                                    <label for="fav_course" class="col-sm-4 offset-sm-1 col-form-label">1. Favorite Course in your Program</label>
                                                    <div class="col-sm-7 mb-2">
                                                        <input type="text" class="form-control" id="fav_course" name="fav_course" placeholder="Enter Favorite Course" value="<?php echo htmlspecialchars($decrypted_fav_course); ?>">
                                                    </div>
                                                </div>    
                                            </div>

                                            <div class="col-sm-12">
                                                <div class="form-group row mb-2">
                                                    <label for="Extra" class="col-sm-4 offset-sm-1 col-form-label">2. Extra-Curricular and Organizations</label>
                                                    <div class="col-sm-4">
                                                        <select class="form-control" id="extra_id" name="extra_id" onchange="toggleExtraSpecifyBox()">
                                                            <option value="" disabled selected>Select</option>
                                                            <?php
                                                            // Fetch options from the database
                                                            $extra_query = "SELECT * FROM tbl_extra_curricular";
                                                            $extra_result = mysqli_query($conn, $extra_query);

                                                            while ($extra = mysqli_fetch_assoc($extra_result)) {
                                                                $selected = ($info_data['extra_id'] == $extra['extra_id']) ? 'selected' : '';
                                                                echo "<option value='{$extra['extra_id']}' $selected>{$extra['extra_name']}</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <input type="text" class="form-control" id="extra_others" name="extra_others" placeholder="Please specify" value="<?php echo isset($info_data['extra_others']) ? htmlspecialchars($decrypted_extra_others) : ''; ?>" disabled>
                                                    </div>
                                                </div> 
                                            </div>

                                            <div class="col-sm-12">
                                                <div class="form-group row mb-2">
                                                    <label for="position" class="col-sm-4 offset-sm-1 col-form-label">3. Position in the Organization</label>
                                                    <div class="col-sm-4">
                                                        <select class="form-control" id="pos_id" name="pos_id" onchange="togglePositionSpecifyBox()">
                                                            <option value="" disabled selected>Select</option>
                                                            <?php
                                                            // Fetch options from the database
                                                            $pos_query = "SELECT * FROM tbl_positions";
                                                            $pos_result = mysqli_query($conn, $pos_query);

                                                            while ($pos = mysqli_fetch_assoc($pos_result)) {
                                                                $selected = ($info_data['pos_id'] == $pos['pos_id']) ? 'selected' : '';
                                                                echo "<option value='{$pos['pos_id']}' $selected>{$pos['pos_name']}</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <input type="text" class="form-control" id="pos_others" name="pos_others" placeholder="Please specify" value="<?php echo isset($info_data['pos_others']) ? htmlspecialchars($decrypted_pos_others) : ''; ?>" disabled>
                                                    </div>
                                                </div> 
                                            </div>
                                            <script>
                                                // Function to handle the specify field based on select input selection
                                                function toggleSpecifyBox(selectId, othersInputId, othersValue) {
                                                    const selectElement = document.getElementById(selectId);
                                                    const othersInput = document.getElementById(othersInputId);
                                                    
                                                    // Check if the "Others" option is selected
                                                    if (selectElement.value === othersValue) {
                                                        othersInput.disabled = false; // Enable the specify input
                                                        othersInput.setAttribute('required', 'required'); // Set the input as required
                                                    } else {
                                                        othersInput.disabled = true; // Disable if any other option
                                                        othersInput.value = ''; // Clear the input if it was previously filled
                                                        othersInput.removeAttribute('required'); // Remove the required attribute
                                                    }
                                                }

                                                // Initialize event listeners for Extra-Curricular and Organizations
                                                document.getElementById('extra_id').addEventListener('change', () => {
                                                    toggleSpecifyBox('extra_id', 'extra_others', '5'); // Assuming '5' is the value for "Others"
                                                });

                                                // Initialize event listeners for Position in the Organization
                                                document.getElementById('pos_id').addEventListener('change', () => {
                                                    toggleSpecifyBox('pos_id', 'pos_others', '2'); // Assuming '2' is the value for "Others"
                                                });

                                                // Call the toggleSpecifyBox on page load for default state
                                                toggleSpecifyBox('extra_id', 'extra_others', '5');
                                                toggleSpecifyBox('pos_id', 'pos_others', '2');
                                            </script>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-sm-12 mb-4">
                                                <h5><b>VIII. Means of Communication</b></h5>
                                            </div>
                                            
                                            <div class="row mb-4">
                                                <div class="col-sm-12">
                                                    <div class="form-group row mb-2">
                                                        <div class="col-sm-2 offset-sm-1">
                                                            <!-- Custom Checkbox -->
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="messenger_checkbox" name="communication[]" value="messenger" style="accent-color: teal;" onchange="toggleInput('messenger_checkbox', 'messenger')" <?php echo isset($info_data['messenger']) && !empty($info_data['messenger']) ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="messenger_checkbox" style="font-weight: bold;">Messenger:</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 mb-2">
                                                            <input type="text" class="form-control" id="messenger" name="messenger" placeholder="Enter Details" value="<?php echo isset($info_data['pos_others']) ? htmlspecialchars($decrypted_messenger) : ''; ?>" disabled>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">
                                                    <div class="form-group row mb-2">
                                                        <div class="col-sm-2 offset-sm-1">
                                                            <!-- Custom Checkbox -->
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="viber_checkbox" name="communication[]" value="viber" style="accent-color: teal;" onchange="toggleInput('viber_checkbox', 'viber')" <?php echo isset($info_data['viber']) && !empty($info_data['viber']) ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="viber_checkbox" style="font-weight: bold;">Viber:</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 mb-2">
                                                            <input type="text" class="form-control" id="viber" name="viber" placeholder="Enter Details" value="<?php echo isset($info_data['pos_others']) ? htmlspecialchars($decrypted_viber) : ''; ?>" disabled>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">
                                                    <div class="form-group row mb-2">
                                                        <div class="col-sm-2 offset-sm-1">
                                                            <!-- Custom Checkbox -->
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="comm_email_checkbox" name="communication[]" value="comm_email" style="accent-color: teal;" onchange="toggleInput('comm_email_checkbox', 'comm_email')" <?php echo isset($info_data['comm_email']) && !empty($info_data['comm_email']) ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="comm_email_checkbox" style="font-weight: bold;">E-mail:</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 mb-2">
                                                            <input type="text" class="form-control" id="comm_email" name="comm_email" placeholder="Enter Details" value="<?php echo isset($info_data['pos_others']) ? htmlspecialchars($decrypted_comm_email) : ''; ?>" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group row mb-2">
                                                        <div class="col-sm-2 offset-sm-1">
                                                            <!-- Custom Checkbox -->
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="comm_other_checkbox" name="communication[]" value="comm_other" style="accent-color: teal;" onchange="toggleInput('comm_other_checkbox', 'comm_other')" <?php echo isset($info_data['comm_other']) && !empty($info_data['comm_other']) ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="comm_other_checkbox" style="font-weight: bold;">Others:</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 mb-2">
                                                            <input type="text" class="form-control" id="comm_other" name="comm_other" placeholder="Enter Details" value="<?php echo isset($info_data['pos_others']) ? htmlspecialchars($decrypted_comm_other) : ''; ?>" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <script>
                                                    // Function to handle enabling/disabling input fields based on checkbox state
                                                    function toggleInput(checkboxId, inputId) {
                                                        const checkbox = document.getElementById(checkboxId);
                                                        const inputField = document.getElementById(inputId);
                                                        
                                                        if (checkbox.checked) {
                                                            inputField.disabled = false; // Enable input if checkbox is checked
                                                            inputField.setAttribute('required', 'required'); // Make input required
                                                        } else {
                                                            inputField.disabled = true; // Disable input if checkbox is unchecked
                                                            inputField.removeAttribute('required'); // Remove required if unchecked
                                                            inputField.value = ''; // Optionally clear the input when disabled
                                                        }
                                                    }

                                                    // Call the toggleInput function on page load for default state
                                                    toggleInput('messenger_checkbox', 'messenger');
                                                    toggleInput('viber_checkbox', 'viber');
                                                    toggleInput('comm_email_checkbox', 'comm_email');
                                                    toggleInput('comm_other_checkbox', 'comm_other');
                                                </script>
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
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
