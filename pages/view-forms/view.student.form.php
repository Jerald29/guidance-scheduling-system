<?php
require '../../includes/session.php';
require_once '../../includes/encryption.php';

// Ensure the user is an administrator
if ($_SESSION['role'] != 'Administrator') {
    $_SESSION['error'] = "Unauthorized access.";
    header('location: ../../pages/dashboard/index.php');
    exit();
}

// Fetch student data, including encrypted fields and foreign key details
$student_id = $_GET['student_id'];
$query = "SELECT stud.student_lname, stud.student_fname, stud.student_mname, stud.img, 
                 info.course_id, info.level_id, info.campus_id, info.address, info.birthdate, 
                 info.birthplace, info.citizenship, info.height, info.weight, info.mobile_no, 
                 info.tel_no, cou.course_name, lev.level_name, cam.campus_name, gen.gender_name, 
                 stat.status_name, bl.blood_name, stud.email, 
                 info.father_name, info.father_occu, info.father_contact, 
                 info.mother_name, info.mother_occu, info.mother_contact, 
                 info.sib1_name, info.sib1_occu, info.sib1_contact, 
                 info.sib2_name, info.sib2_occu, info.sib2_contact, 
                 info.sib3_name, info.sib3_occu, info.sib3_contact, 
                 info.sib4_name, info.sib4_occu, info.sib4_contact, 
                 info.sib5_name, info.sib5_occu, info.sib5_contact, 
                 info.sib6_name, info.sib6_occu, info.sib6_contact, 
                 info.sib7_name, info.sib7_occu, info.sib7_contact,
                 info.gs_name, info.gs_type, info.gs_year, info.gs_honor,
                 info.jhs_name, info.jhs_type, info.jhs_year, info.jhs_honor,
                 info.shs_name, info.shs_type, info.shs_year, info.shs_honor,
                 info.vs_name, info.vs_type, info.vs_year, info.vs_honor,
                 info.college_name, info.college_type, info.college_year, info.college_honor,
                 info.org1_name, info.org1_serve, info.org1_date,
                 info.org2_name, info.org2_serve, info.org2_date,
                 info.org3_name, info.org3_serve, info.org3_date,
                 info.marital_id, mar.marital_name, info.marital_others,
                 info.finance_id, fin.finance_name,
                 info.allowance_id, alw.allowance_name, info.allowance_others,
                 info.income_id, inc.income_name,
                 info.residence_id, res.residence_name, info.residence_others,
                 info.dose1_name, info.dose1_date, info.dose2_name, info.dose2_date,
                 info.boost1_name, info.boost1_date, info.boost2_name, info.boost2_date,
                 info.vaccination, info.vitamin, info.maintenance,
                 info.vision_specify, info.hearing_specify, info.speech_specify, info.genhealth_specify, info.health_con,
                 info.psychiatrist_when, info.psychiatrist_what, info.psychologist_when, info.psychologist_what,
                 info.counselor_when, info.counselor_what, info.fav_course, info.extra_id, ext.extra_name, info.extra_others,
                 info.pos_id, pos.pos_name, info.pos_others, info.messenger, info.viber, info.comm_email, info.comm_other
          FROM tbl_students stud
          JOIN tbl_stud_info info ON stud.student_id = info.student_id
          LEFT JOIN tbl_campuses cam ON info.campus_id = cam.campus_id
          LEFT JOIN tbl_courses cou ON info.course_id = cou.course_id
          LEFT JOIN tbl_levels lev ON info.level_id = lev.level_id
          LEFT JOIN tbl_genders gen ON info.gender_id = gen.gender_id
          LEFT JOIN tbl_statuses stat ON info.status_id = stat.status_id
          LEFT JOIN tbl_bloodtypes bl ON info.blood_id = bl.blood_id
          LEFT JOIN tbl_marital mar ON info.marital_id = mar.marital_id
          LEFT JOIN tbl_finances fin ON info.finance_id = fin.finance_id
          LEFT JOIN tbl_allowance alw ON info.allowance_id = alw.allowance_id
          LEFT JOIN tbl_income inc ON info.income_id = inc.income_id
          LEFT JOIN tbl_residence res ON info.residence_id = res.residence_id
          LEFT JOIN tbl_extra_curricular ext ON info.extra_id = ext.extra_id
          LEFT JOIN tbl_positions pos on info.pos_id = pos.pos_id
          WHERE stud.student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student_data = $result->fetch_assoc();

// Decryption
$decrypted_address = $student_data['address'] !== null ? decrypt($student_data['address']) : null;
$decrypted_birthdate = $student_data['birthdate'] !== null ? decrypt($student_data['birthdate']) : null;
$decrypted_birthplace = $student_data['birthplace'] !== null ? decrypt($student_data['birthplace']) : null;
$decrypted_citizenship = $student_data['citizenship'] !== null ? decrypt($student_data['citizenship']) : null;
$decrypted_height = $student_data['height'] !== null ? decrypt($student_data['height']) : null;
$decrypted_weight = $student_data['weight'] !== null ? decrypt($student_data['weight']) : null;
$decrypted_mobile_no = $student_data['mobile_no'] !== null ? decrypt($student_data['mobile_no']) : null;
$decrypted_tel_no = $student_data['tel_no'] !== null ? decrypt($student_data['tel_no']) : null;
$decrypted_father_name = $student_data['father_name'] !== null ? decrypt($student_data['father_name']) : null;
$decrypted_father_occu = $student_data['father_occu'] !== null ? decrypt($student_data['father_occu']) : null;
$decrypted_father_contact = $student_data['father_contact'] !== null ? decrypt($student_data['father_contact']) : null;
$decrypted_mother_name = $student_data['mother_name'] !== null ? decrypt($student_data['mother_name']) : null;
$decrypted_mother_occu = $student_data['mother_occu'] !== null ? decrypt($student_data['mother_occu']) : null;
$decrypted_mother_contact = $student_data['mother_contact'] !== null ? decrypt($student_data['mother_contact']) : null;

// Loop for each sibling :)
for ($i = 1; $i <= 7; $i++) {
    ${"decrypted_sib{$i}_name"} = $student_data["sib{$i}_name"] !== null ? decrypt($student_data["sib{$i}_name"]) : null;
    ${"decrypted_sib{$i}_occu"} = $student_data["sib{$i}_occu"] !== null ? decrypt($student_data["sib{$i}_occu"]) : null;
    ${"decrypted_sib{$i}_contact"} = $student_data["sib{$i}_contact"] !== null ? decrypt($student_data["sib{$i}_contact"]) : null;
}

$decrypted_gs_name = $student_data['gs_name'] !== null ? decrypt($student_data['gs_name']) : null;
$decrypted_gs_type = $student_data['gs_type'] !== null ? decrypt($student_data['gs_type']) : null;
$decrypted_gs_year = $student_data['gs_year'] !== null ? decrypt($student_data['gs_year']) : null;
$decrypted_gs_honor = $student_data['gs_honor'] !== null ? decrypt($student_data['gs_honor']) : null;
$decrypted_jhs_name = $student_data['jhs_name'] !== null ? decrypt($student_data['jhs_name']) : null;
$decrypted_jhs_type = $student_data['jhs_type'] !== null ? decrypt($student_data['jhs_type']) : null;
$decrypted_jhs_year = $student_data['jhs_year'] !== null ? decrypt($student_data['jhs_year']) : null;
$decrypted_jhs_honor = $student_data['jhs_honor'] !== null ? decrypt($student_data['jhs_honor']) : null;
$decrypted_shs_name = $student_data['shs_name'] !== null ? decrypt($student_data['shs_name']) : null;
$decrypted_shs_type = $student_data['shs_type'] !== null ? decrypt($student_data['shs_type']) : null;
$decrypted_shs_year = $student_data['shs_year'] !== null ? decrypt($student_data['shs_year']) : null;
$decrypted_shs_honor = $student_data['shs_honor'] !== null ? decrypt($student_data['shs_honor']) : null;
$decrypted_vs_name = $student_data['vs_name'] !== null ? decrypt($student_data['vs_name']) : null;
$decrypted_vs_type = $student_data['vs_type'] !== null ? decrypt($student_data['vs_type']) : null;
$decrypted_vs_year = $student_data['vs_year'] !== null ? decrypt($student_data['vs_year']) : null;
$decrypted_vs_honor = $student_data['vs_honor'] !== null ? decrypt($student_data['vs_honor']) : null;
$decrypted_college_name = $student_data['college_name'] !== null ? decrypt($student_data['college_name']) : null;
$decrypted_college_type = $student_data['college_type'] !== null ? decrypt($student_data['college_type']) : null;
$decrypted_college_year = $student_data['college_year'] !== null ? decrypt($student_data['college_year']) : null;
$decrypted_college_honor = $student_data['college_honor'] !== null ? decrypt($student_data['college_honor']) : null;

// Loop for organizations
$decrypted_orgs = [];
for ($i = 1; $i <= 3; $i++) {
    $decrypted_orgs[$i]['name'] = !empty($student_data["org{$i}_name"]) ? decrypt($student_data["org{$i}_name"]) : null;
    $decrypted_orgs[$i]['serve'] = !empty($student_data["org{$i}_serve"]) ? decrypt($student_data["org{$i}_serve"]) : null;
    $decrypted_orgs[$i]['date'] = !empty($student_data["org{$i}_date"]) ? decrypt($student_data["org{$i}_date"]) : null;
}

$decrypted_marital_others = $student_data['marital_others'] !== null ? decrypt($student_data['marital_others']) : null;
$decrypted_allowance_others = $student_data['allowance_others'] !== null ? decrypt($student_data['allowance_others']) : null;
$decrypted_residence_others = $student_data['residence_others'] !== null ? decrypt($student_data['residence_others']) : null;
$decrypted_dose1_name = $student_data['dose1_name'] !== null ? decrypt($student_data['dose1_name']) : null;
$decrypted_dose1_date = $student_data['dose1_date'] !== null ? decrypt($student_data['dose1_date']) : null;
$decrypted_dose2_name = $student_data['dose2_name'] !== null ? decrypt($student_data['dose2_name']) : null;
$decrypted_dose2_date = $student_data['dose2_date'] !== null ? decrypt($student_data['dose2_date']) : null;
$decrypted_boost1_name = $student_data['boost1_name'] !== null ? decrypt($student_data['boost1_name']) : null;
$decrypted_boost1_date = $student_data['boost1_date'] !== null ? decrypt($student_data['boost1_date']) : null;
$decrypted_boost2_name = $student_data['boost2_name'] !== null ? decrypt($student_data['boost2_name']) : null;
$decrypted_boost2_date = $student_data['boost2_date'] !== null ? decrypt($student_data['boost2_date']) : null;
$decrypted_vaccination = $student_data['vaccination'] !== null ? decrypt($student_data['vaccination']) : null;
$decrypted_vitamin = $student_data['vitamin'] !== null ? decrypt($student_data['vitamin']) : null;
$decrypted_maintenance = $student_data['maintenance'] !== null ? decrypt($student_data['maintenance']) : null;
$decrypted_vision_specify = $student_data['vision_specify'] !== null ? decrypt($student_data['vision_specify']) : null;
$decrypted_hearing_specify = $student_data['hearing_specify'] !== null ? decrypt($student_data['hearing_specify']) : null;
$decrypted_speech_specify = $student_data['speech_specify'] !== null ? decrypt($student_data['speech_specify']) : null;
$decrypted_genhealth_specify = $student_data['genhealth_specify'] !== null ? decrypt($student_data['genhealth_specify']) : null;
$decrypted_health_con = $student_data['health_con'] !== null ? decrypt($student_data['health_con']) : null;
$decrypted_psychiatrist_when = $student_data['psychiatrist_when'] !== null ? decrypt($student_data['psychiatrist_when']) : null;
$decrypted_psychiatrist_what = $student_data['psychiatrist_what'] !== null ? decrypt($student_data['psychiatrist_what']) : null;
$decrypted_psychologist_when = $student_data['psychologist_when'] !== null ? decrypt($student_data['psychologist_when']) : null;
$decrypted_psychologist_what = $student_data['psychologist_what'] !== null ? decrypt($student_data['psychologist_what']) : null;
$decrypted_counselor_when = $student_data['counselor_when'] !== null ? decrypt($student_data['counselor_when']) : null;
$decrypted_counselor_what = $student_data['counselor_what'] !== null ? decrypt($student_data['counselor_what']) : null;
$decrypted_fav_course = $student_data['fav_course'] !== null ? decrypt($student_data['fav_course']) : null;
$decrypted_extra_others = $student_data['extra_others'] !== null ? decrypt($student_data['extra_others']) : null;
$decrypted_pos_others = $student_data['pos_others'] !== null ? decrypt($student_data['pos_others']) : null;
$decrypted_messenger = $student_data['messenger'] !== null ? decrypt($student_data['messenger']) : null;
$decrypted_viber = $student_data['viber'] !== null ? decrypt($student_data['viber']) : null;
$decrypted_comm_email = $student_data['comm_email'] !== null ? decrypt($student_data['comm_email']) : null;
$decrypted_comm_other = $student_data['comm_other'] !== null ? decrypt($student_data['comm_other']) : null;



$stmt->close();  // Close the statement
$conn->close();  // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student's Profile Form | GCS Bacoor</title>
    <link rel="icon" href="../../docs/assets/img/gcs-bac.png">
    <?php include '../../includes/links.php'; ?>
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
                            <h1 class="m-0">View Student's Profile</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../dashboard/index.php">Home</a></li>
                                <li class="breadcrumb-item active">View Student's Profile</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header bg-olive text-white">
                            <h3 class="card-title">Student: <b><?php echo htmlspecialchars($student_data['student_lname'] . ', ' . $student_data['student_fname'] . ' ' . $student_data['student_mname']); ?></b></h3>
                        </div>
                        <div class="card-body" style="background: #f4f6f9; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <?php
                                    // Display student image or default image with hover effect
                                    if (!empty($student_data['img'])) {
                                        echo '<img src="data:image/jpeg;base64,' . base64_encode($student_data['img']) . '" class="img-fluid rounded-circle shadow-lg" alt="Student Image" style="margin-top: 15px; width: 150px; height: 150px; transition: transform 0.3s;">';
                                    } else {
                                        echo '<img src="../../docs/assets/img/user2.png" class="img-fluid rounded-circle shadow-lg" alt="Default User Image" style="margin-top: 15px; width: 150px; height: 150px; transition: transform 0.3s;">';
                                    }
                                    ?>
                                </div>
                                <div class="col-md-9">
                                   <div class="card mb-3 mt-3">
                                        <div class="card-header bg-olive text-white d-flex align-items-center" style="cursor: pointer; padding: 12px 20px; font-size: 18px;" data-toggle="collapse" data-target="#personalInfoBox" aria-expanded="true" aria-controls="personalInfoBox" id="personalInfoHeader">
                                            <span><b>I. Personal Information</b></span>
                                            <i class="fa fa-chevron-up ml-auto arrow-icon"></i> 
                                        </div>

                                        <div id="personalInfoBox" class="collapse show">
                                            <div class="card-body" style="background: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);">
                                                <p class="mb-2"><strong>Fullname:</strong> <span class="text-dark"><?php echo htmlspecialchars($student_data['student_lname'] . ', ' . $student_data['student_fname'] . ' ' . $student_data['student_mname']); ?></span></p>
                                                <p class="mb-2"><strong>Campus:</strong> <span class="text-dark"><?php echo htmlspecialchars($student_data['campus_name']); ?></span></p>
                                                <p class="mb-2"><strong>Program:</strong> <span class="text-dark"><?php echo htmlspecialchars($student_data['course_name']); ?></span></p>
                                                <p class="mb-2"><strong>Level:</strong> <span class="text-dark"><?php echo htmlspecialchars($student_data['level_name']); ?></span></p>
                                                <p class="mb-2"><strong>Address:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_address); ?></span></p>
                                                <p class="mb-2"><strong>Date of Birth:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_birthdate); ?></span></p>
                                                <p class="mb-2"><strong>Place of Birth:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_birthplace); ?></span></p>
                                                <p class="mb-2"><strong>Sex:</strong> <span class="text-dark"><?php echo htmlspecialchars($student_data['gender_name']); ?></span></p>
                                                <p class="mb-2"><strong>Civil Status:</strong> <span class="text-dark"><?php echo htmlspecialchars($student_data['status_name']); ?></span></p>
                                                <p class="mb-2"><strong>Citizenship:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_citizenship); ?></span></p>
                                                <p class="mb-2"><strong>Height (cm):</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_height); ?></span></p>
                                                <p class="mb-2"><strong>Weight (kg):</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_weight); ?></span></p>
                                                <p class="mb-2"><strong>Blood Type:</strong> <span class="text-dark"><?php echo htmlspecialchars($student_data['blood_name']); ?></span></p>
                                                <p class="mb-2"><strong>Mobile Number:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_mobile_no); ?></span></p>
                                                <p class="mb-2"><strong>Telephone Number:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_tel_no); ?></span></p>
                                                <p class="mb-2"><strong>Email:</strong> <span class="text-dark"><?php echo htmlspecialchars($student_data['email']); ?></span></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <script>
                                        document.getElementById("personalInfoHeader").addEventListener("click", function() {
                                            const arrowIcon = this.querySelector(".arrow-icon");
                                            arrowIcon.classList.toggle("rotate-arrow");
                                        });
                                    </script>

                                    <style>
                                        .arrow-icon {
                                            transition: transform 0.3s ease;
                                        }
                                        .rotate-arrow {
                                            transform: rotate(180deg);
                                        }
                                    </style>

                                    <div class="card mb-3 mt-3">
                                        <div class="card-header bg-olive text-white d-flex align-items-center" style="cursor: pointer; padding: 12px 20px; font-size: 18px;" data-toggle="collapse" data-target="#familyBackgroundBox" aria-expanded="false" aria-controls="familyBackgroundBox" id="familyBackgroundHeader">
                                            <span><b>II. Family Background</b></span>
                                            <i class="fa fa-chevron-down ml-auto arrow-icon"></i> 
                                        </div>

                                        <div id="familyBackgroundBox" class="collapse">
                                            <div class="card-body" style="background: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);">
                                                <div class="row mb-2">
                                                    <div class="col-md-4"><strong>Father's Name:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_father_name); ?></span></div>
                                                    <div class="col-md-3"><strong>Occupation:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_father_occu); ?></span></div>
                                                    <div class="col-md-2"><strong>Contact:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_father_contact); ?></span></div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-4"><strong>Mother's Name:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_mother_name); ?></span></div>
                                                    <div class="col-md-3"><strong>Occupation:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_mother_occu); ?></span></div>
                                                 <div class="col-md-2"><strong>Contact:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_mother_contact); ?></span></div>
                                                </div>
                                                <div class="mb-2">
                                                <span style="font-weight: bold; text-decoration: underline;">Siblings/Children</span>
                                                    <span style="font-style: italic; text-decoration: underline;"> (From First to Last Child/Sibling)</span>
                                                </div>
                                                <div>
                                                    <?php
                                                    for ($i = 1; $i <= 7; $i++) {
                                                        // Loop for each sibling :)
                                                        $name = ${"decrypted_sib{$i}_name"};
                                                        $occu = ${"decrypted_sib{$i}_occu"};
                                                        $contact = ${"decrypted_sib{$i}_contact"};

                                                        echo '<div class="row mb-2">';
                                                        echo '    <div class="col-md-4"><strong>Complete Name:</strong> <span class="text-dark">' . htmlspecialchars($name) . '</span></div>';
                                                        echo '    <div class="col-md-3"><strong>Occupation:</strong> <span class="text-dark">' . htmlspecialchars($occu) . '</span></div>';
                                                        echo '    <div class="col-md-2"><strong>Contact:</strong> <span class="text-dark">' . htmlspecialchars($contact) . '</span></div>';
                                                        echo '</div>';
                                                    }
                                                    ?>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mb-3 mt-3">
                                        <div class="card-header bg-olive text-white d-flex align-items-center" style="cursor: pointer; padding: 12px 20px; font-size: 18px;" data-toggle="collapse" data-target="#educationalBackgroundBox" aria-expanded="false" aria-controls="educationalBackgroundBox" id="educationalBackgroundHeader">
                                            <span><b>III. Educational Background</b></span>
                                            <i class="fa fa-chevron-down ml-auto arrow-icon"></i>
                                        </div>

                                        <div id="educationalBackgroundBox" class="collapse">
                                            <div class="card-body" style="background: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);">
                                                <div class="row mb-2">
                                                    <div class="col-md-5"><strong>Grade School:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_gs_name); ?></span></div>
                                                    <div class="col-md-2"><strong>Type of School:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_gs_type); ?></span></div>
                                                    <div class="col-md-1"><strong>Year:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_gs_year); ?></span></div>
                                                    <div class="col-md-3"><strong>Honors Received:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_gs_honor); ?></span></div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-5"><strong>Junior High School:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_jhs_name); ?></span></div>
                                                    <div class="col-md-2"><strong>Type of School:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_jhs_type); ?></span></div>
                                                    <div class="col-md-1"><strong>Year:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_jhs_year); ?></span></div>
                                                    <div class="col-md-3"><strong>Honors Received:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_jhs_honor); ?></span></div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-5"><strong>Senior High School:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_shs_name); ?></span></div>
                                                    <div class="col-md-2"><strong>Type of School:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_shs_type); ?></span></div>
                                                    <div class="col-md-1"><strong>Year:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_shs_year); ?></span></div>
                                                    <div class="col-md-3"><strong>Honors Received:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_shs_honor); ?></span></div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-5"><strong>Vocational School:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_vs_name); ?></span></div>
                                                    <div class="col-md-2"><strong>Type of School:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_vs_type); ?></span></div>
                                                    <div class="col-md-1"><strong>Year:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_vs_year); ?></span></div>
                                                    <div class="col-md-3"><strong>Honors Received:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_vs_honor); ?></span></div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-5"><strong>Undergraduate/ College:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_college_name); ?></span></div>
                                                    <div class="col-md-2"><strong>Type of School:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_college_type); ?></span></div>
                                                    <div class="col-md-1"><strong>Year:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_college_year); ?></span></div>
                                                    <div class="col-md-3"><strong>Honors Received:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_college_honor); ?></span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mb-3 mt-3">
                                        <div class="card-header bg-olive text-white d-flex align-items-center" style="cursor: pointer; padding: 12px 20px; font-size: 18px;" data-toggle="collapse" data-target="#voluntaryWorkBox" aria-expanded="false" aria-controls="voluntaryWorkBox" id="voluntaryWorkHeader">
                                            <span><b>IV. Voluntary Work/ Athletical Affiliation</b></span>
                                            <i class="fa fa-chevron-down ml-auto arrow-icon"></i>
                                        </div>

                                        <div id="voluntaryWorkBox" class="collapse">
                                            <div class="card-body" style="background: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);">
                                                <?php for ($i = 1; $i <= 3; $i++): ?>
                                                    <div class="row mb-2">
                                                        <div class="col-md-5">
                                                            <strong>Organization/ Athletics:</strong> 
                                                            <span class="text-dark"><?php echo htmlspecialchars($decrypted_orgs[$i]['name']); ?></span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <strong>Service/s Rendered:</strong> 
                                                            <span class="text-dark"><?php echo htmlspecialchars($decrypted_orgs[$i]['serve']); ?></span>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <strong>Date:</strong> 
                                                            <span class="text-dark"><?php echo htmlspecialchars($decrypted_orgs[$i]['date']); ?></span>
                                                        </div>
                                                    </div>
                                                <?php endfor; ?>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="card mb-3 mt-3">
                                        <div class="card-header bg-olive text-white d-flex align-items-center" style="cursor: pointer; padding: 12px 20px; font-size: 18px;" data-toggle="collapse" data-target="#studentLifeBox" aria-expanded="false" aria-controls="studentLifeBox" id="studentLifeHeader">
                                            <span><b>V. Student’s Life Information</b></span>
                                            <i class="fa fa-chevron-down ml-auto arrow-icon"></i>
                                        </div>

                                        <div id="studentLifeBox" class="collapse">
                                            <div class="card-body" style="background: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);">
                                                <div class="row mb-3">
                                                    <div class="col-md-6"><strong>1. Parent’s Marital Status:</strong> <span class="text-dark"><?php echo htmlspecialchars($student_data['marital_name']); ?></span></div>
                                                    <div class="col-md-4"><strong>Specify:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_marital_others); ?></span></div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-6"><strong>2. Who finances your schooling?</strong> <span class="text-dark"><?php echo htmlspecialchars($student_data['finance_name']); ?></span></div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-6"><strong>3. How much is your daily allowance?</strong> <span class="text-dark"><?php echo htmlspecialchars($student_data['allowance_name']); ?></span></div>
                                                    <div class="col-md-4"><strong>Specify:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_allowance_others); ?></span></div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-6"><strong>4. Family Income:</strong> <span class="text-dark"><?php echo htmlspecialchars($student_data['income_name']); ?></span></div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-6"><strong>5. Nature of Residence:</strong> <span class="text-dark"><?php echo htmlspecialchars($student_data['residence_name']); ?></span></div>
                                                    <div class="col-md-4"><strong>Specify:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_residence_others); ?></span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mb-3 mt-3">
                                        <div class="card-header bg-olive text-white d-flex align-items-center" style="cursor: pointer; padding: 12px 20px; font-size: 18px;" data-toggle="collapse" data-target="#healthInformationBox" aria-expanded="false" aria-controls="healthInformationBox" id="healthInformationHeader">
                                            <span><b>VI. Health Information</b></span>
                                            <i class="fa fa-chevron-down ml-auto arrow-icon"></i>
                                        </div>

                                        <div id="healthInformationBox" class="collapse">
                                            <div class="card-body" style="background: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);">
                                                <div class="row mb-2">
                                                    <div class="col-md-4"><strong>1st Dose:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_dose1_name); ?></span></div>
                                                    <div class="col-md-2"><strong>Date:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_dose1_date); ?></span></div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-4"><strong>2nd Dose:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_dose2_name); ?></span></div>
                                                    <div class="col-md-2"><strong>Date:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_dose2_date); ?></span></div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-4"><strong>Booster 1:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_boost1_name); ?></span></div>
                                                    <div class="col-md-2"><strong>Date:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_boost1_date); ?></span></div>
                                                </div>
                                                <div class="row mb-4">
                                                    <div class="col-md-4"><strong>Booster 2:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_boost2_name); ?></span></div>
                                                    <div class="col-md-2"><strong>Date:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_boost2_date); ?></span></div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-4"><strong>Other Vaccination:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_vaccination); ?></span></div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-4"><strong>Vitamins/Supplements:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_vitamin); ?></span></div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-4"><strong>Maintenance:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_maintenance); ?></span></div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-4"><strong>Vision:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_vision_specify); ?></span></div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-4"><strong>Hearing:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_hearing_specify); ?></span></div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-4"><strong>Speech:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_speech_specify); ?></span></div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-4"><strong>General Health:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_genhealth_specify); ?></span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mb-3 mt-3">
                                        <div class="card-header bg-olive text-white d-flex align-items-center" style="cursor: pointer; padding: 12px 20px; font-size: 18px;" data-toggle="collapse" data-target="#interestHobbiesBox" aria-expanded="false" aria-controls="interestHobbiesBox" id="interestHobbiesHeader">
                                            <span><b>VII. Interest and Hobbies</b></span>
                                            <i class="fa fa-chevron-down ml-auto arrow-icon"></i>
                                        </div>

                                        <div id="interestHobbiesBox" class="collapse">
                                            <div class="card-body" style="background: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);">
                                                <div class="row mb-3">
                                                    <div class="col-md-6"><strong>1. Favorite Course in your Program:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_fav_course); ?></span></div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-6"><strong>2. Extra-Curricular and Organizations:</strong> <span class="text-dark"><?php echo htmlspecialchars($student_data['extra_name']); ?></span></div>
                                                    <div class="col-md-4"><strong>Specify:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_extra_others); ?></span></div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-6"><strong>3. Position in the Organization:</strong> <span class="text-dark"><?php echo htmlspecialchars($student_data['pos_name']); ?></span></div>
                                                    <div class="col-md-4"><strong>Specify:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_pos_others); ?></span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mb-3 mt-3">
                                        <div class="card-header bg-olive text-white d-flex align-items-center" style="cursor: pointer; padding: 12px 20px; font-size: 18px;" data-toggle="collapse" data-target="#meansCommunicationBox" aria-expanded="false" aria-controls="meansCommunicationBox" id="meansCommunicationHeader">
                                            <span><b>VIII. Means of Communication</b></span>
                                            <i class="fa fa-chevron-down ml-auto arrow-icon"></i>
                                        </div>

                                        <div id="meansCommunicationBox" class="collapse">
                                            <div class="card-body" style="background: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);">
                                                <div class="row mb-3">
                                                    <div class="col-md-6"><strong>Messenger:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_messenger); ?></span></div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-6"><strong>Viber:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_viber); ?></span></div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-6"><strong>E-mail:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_comm_email); ?></span></div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-6"><strong>Others:</strong> <span class="text-dark"><?php echo htmlspecialchars($decrypted_comm_other); ?></span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <script>
                                        // For arrow expand/collapse arrow animation
                                        document.getElementById("familyBackgroundHeader").addEventListener("click", function() {
                                            const arrowIcon = this.querySelector(".arrow-icon");
                                            arrowIcon.classList.toggle("rotate-arrow");
                                        });

                                        document.getElementById("educationalBackgroundHeader").addEventListener("click", function() {
                                            const arrowIcon = this.querySelector(".arrow-icon");
                                            arrowIcon.classList.toggle("rotate-arrow");
                                        });
                                        document.getElementById("voluntaryWorkHeader").addEventListener("click", function() {
                                            const arrowIcon = this.querySelector(".arrow-icon");
                                            arrowIcon.classList.toggle("rotate-arrow");
                                        });
                                        document.getElementById("studentLifeHeader").addEventListener("click", function() {
                                            const arrowIcon = this.querySelector(".arrow-icon");
                                            arrowIcon.classList.toggle("rotate-arrow");
                                        });
                                        document.getElementById("healthInformationHeader").addEventListener("click", function() {
                                            const arrowIcon = this.querySelector(".arrow-icon");
                                            arrowIcon.classList.toggle("rotate-arrow");
                                        });
                                        document.getElementById("interestHobbiesHeader").addEventListener("click", function() {
                                            const arrowIcon = this.querySelector(".arrow-icon");
                                            arrowIcon.classList.toggle("rotate-arrow");
                                        });
                                        document.getElementById("meansCommunicationHeader").addEventListener("click", function() {
                                            const arrowIcon = this.querySelector(".arrow-icon");
                                            arrowIcon.classList.toggle("rotate-arrow");
                                        });
                                    </script> 

                                    <style>
                                        .arrow-icon {
                                            transition: transform 0.3s ease;
                                        }
                                        .rotate-arrow {
                                            transform: rotate(180deg);
                                        }
                                    </style>

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

        <?php include '../../includes/script.php'; ?>
    </div>
</body>
</html>

