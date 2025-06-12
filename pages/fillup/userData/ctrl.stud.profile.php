<?php
require '../../../includes/session.php';
require_once '../../../includes/encryption.php';

if (isset($_POST['submit'])) {
    $student_id = $_SESSION['id'];

    // Fetch existing student info
    $stmt = $conn->prepare("SELECT * FROM tbl_stud_info WHERE student_id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $info_exists = $result->num_rows > 0;

    // (I. Personal Information - Required)
    $campus_id = isset($_POST['campus']) ? $_POST['campus'] : '';
    $course_id = isset($_POST['course']) ? $_POST['course'] : '';
    $level_id = isset($_POST['level']) ? $_POST['level'] : '';
    $address = isset($_POST['address']) ? $_POST['address'] : '';
    $birthdate = isset($_POST['birthdate']) ? $_POST['birthdate'] : '';
    $birthplace = isset($_POST['birthplace']) ? $_POST['birthplace'] : '';
    $gender_id = isset($_POST['gender']) ? $_POST['gender'] : '';
    $status_id = isset($_POST['status']) ? $_POST['status'] : '';
    $citizenship = isset($_POST['citizenship']) ? $_POST['citizenship'] : '';
    $height = isset($_POST['height']) ? $_POST['height'] : '';
    $weight = isset($_POST['weight']) ? $_POST['weight'] : '';
    $blood_id = isset($_POST['blood_id']) ? $_POST['blood_id'] : '';
    $mobile_no = isset($_POST['mobile_no']) ? $_POST['mobile_no'] : '';
    $tel_no = isset($_POST['tel_no']) ? $_POST['tel_no'] : '';

    // Encrypt the data before saving it to the database
    $encrypted_address = $address ? encrypt($address) : '';
    $encrypted_birthdate = $birthdate ? encrypt($birthdate) : '';
    $encrypted_birthplace = $birthplace ? encrypt($birthplace) : '';
    $encrypted_citizenship = $citizenship ? encrypt($citizenship) : '';
    $encrypted_height = $height ? encrypt($height) : '';
    $encrypted_weight = $weight ? encrypt($weight) : '';
    $encrypted_mobile_no = $mobile_no ? encrypt($mobile_no) : '';
    $encrypted_tel_no = $tel_no ? encrypt($tel_no) : '';


    //(II. Family Background - Optional for now)
    $father_name = isset($_POST['father_name']) ? $_POST['father_name'] : null;
    $father_occu = isset($_POST['father_occu']) ? $_POST['father_occu'] : null;
    $father_contact = isset($_POST['father_contact']) ? $_POST['father_contact'] : null;
    $mother_name = isset($_POST['mother_name']) ? $_POST['mother_name'] : null;
    $mother_occu = isset($_POST['mother_occu']) ? $_POST['mother_occu'] : null;
    $mother_contact = isset($_POST['mother_contact']) ? $_POST['mother_contact'] : null;
    $sib1_name = isset($_POST['sib1_name']) ? $_POST['sib1_name'] : null;
    $sib2_name = isset($_POST['sib2_name']) ? $_POST['sib2_name'] : null;
    $sib3_name = isset($_POST['sib3_name']) ? $_POST['sib3_name'] : null;
    $sib4_name = isset($_POST['sib4_name']) ? $_POST['sib4_name'] : null;
    $sib5_name = isset($_POST['sib5_name']) ? $_POST['sib5_name'] : null;
    $sib6_name = isset($_POST['sib6_name']) ? $_POST['sib6_name'] : null;
    $sib7_name = isset($_POST['sib7_name']) ? $_POST['sib7_name'] : null;
    $sib1_occu = isset($_POST['sib1_occu']) ? $_POST['sib1_occu'] : null;
    $sib2_occu = isset($_POST['sib2_occu']) ? $_POST['sib2_occu'] : null;
    $sib3_occu = isset($_POST['sib3_occu']) ? $_POST['sib3_occu'] : null;
    $sib4_occu = isset($_POST['sib4_occu']) ? $_POST['sib4_occu'] : null;
    $sib5_occu = isset($_POST['sib5_occu']) ? $_POST['sib5_occu'] : null;
    $sib6_occu = isset($_POST['sib6_occu']) ? $_POST['sib6_occu'] : null;
    $sib7_occu = isset($_POST['sib7_occu']) ? $_POST['sib7_occu'] : null;
    $sib1_contact = isset($_POST['sib1_contact']) ? $_POST['sib1_contact'] : null;
    $sib2_contact = isset($_POST['sib2_contact']) ? $_POST['sib2_contact'] : null;
    $sib3_contact = isset($_POST['sib3_contact']) ? $_POST['sib3_contact'] : null;
    $sib4_contact = isset($_POST['sib4_contact']) ? $_POST['sib4_contact'] : null;
    $sib5_contact = isset($_POST['sib5_contact']) ? $_POST['sib5_contact'] : null;
    $sib6_contact = isset($_POST['sib6_contact']) ? $_POST['sib6_contact'] : null;
    $sib7_contact = isset($_POST['sib7_contact']) ? $_POST['sib7_contact'] : null;

    // Encrypt the data before saving it to the database
    $encrypted_father_name = $father_name ? encrypt($father_name) : null;
    $encrypted_father_occu = $father_occu ? encrypt($father_occu) : null;
    $encrypted_father_contact = $father_contact ? encrypt($father_contact) : null;
    $encrypted_mother_name = $mother_name ? encrypt($mother_name) : null;
    $encrypted_mother_occu = $mother_occu ? encrypt($mother_occu) : null;
    $encrypted_mother_contact = $mother_contact ? encrypt($mother_contact) : null;
    $encrypted_sib1_name = $sib1_name ? encrypt($sib1_name) : null;
    $encrypted_sib2_name = $sib2_name ? encrypt($sib2_name) : null;
    $encrypted_sib3_name = $sib3_name ? encrypt($sib3_name) : null;
    $encrypted_sib4_name = $sib4_name ? encrypt($sib4_name) : null;
    $encrypted_sib5_name = $sib5_name ? encrypt($sib5_name) : null;
    $encrypted_sib6_name = $sib6_name ? encrypt($sib6_name) : null;
    $encrypted_sib7_name = $sib7_name ? encrypt($sib7_name) : null;
    $encrypted_sib1_occu = $sib1_occu ? encrypt($sib1_occu) : null;
    $encrypted_sib2_occu = $sib2_occu ? encrypt($sib2_occu) : null;
    $encrypted_sib3_occu = $sib3_occu ? encrypt($sib3_occu) : null;
    $encrypted_sib4_occu = $sib4_occu ? encrypt($sib4_occu) : null;
    $encrypted_sib5_occu = $sib5_occu ? encrypt($sib5_occu) : null;
    $encrypted_sib6_occu = $sib6_occu ? encrypt($sib6_occu) : null;
    $encrypted_sib7_occu = $sib7_occu ? encrypt($sib7_occu) : null;
    $encrypted_sib1_contact = $sib1_contact ? encrypt($sib1_contact) : null;
    $encrypted_sib2_contact = $sib2_contact ? encrypt($sib2_contact) : null;
    $encrypted_sib3_contact = $sib3_contact ? encrypt($sib3_contact) : null;
    $encrypted_sib4_contact = $sib4_contact ? encrypt($sib4_contact) : null;
    $encrypted_sib5_contact = $sib5_contact ? encrypt($sib5_contact) : null;
    $encrypted_sib6_contact = $sib6_contact ? encrypt($sib6_contact) : null;
    $encrypted_sib7_contact = $sib7_contact ? encrypt($sib7_contact) : null;

    //(III. Educational Background - Optional for now)
    $gs_name = isset($_POST['gs_name']) ? $_POST['gs_name'] : null;
    $gs_type = isset($_POST['gs_type']) ? $_POST['gs_type'] : null;
    $gs_year = isset($_POST['gs_year']) ? $_POST['gs_year'] : null;
    $gs_honor = isset($_POST['gs_honor']) ? $_POST['gs_honor'] : null;
    $jhs_name = isset($_POST['jhs_name']) ? $_POST['jhs_name'] : null;
    $jhs_type = isset($_POST['jhs_type']) ? $_POST['jhs_type'] : null;
    $jhs_year = isset($_POST['jhs_year']) ? $_POST['jhs_year'] : null;
    $jhs_honor = isset($_POST['jhs_honor']) ? $_POST['jhs_honor'] : null;
    $shs_name = isset($_POST['shs_name']) ? $_POST['shs_name'] : null;
    $shs_type = isset($_POST['shs_type']) ? $_POST['shs_type'] : null;
    $shs_year = isset($_POST['shs_year']) ? $_POST['shs_year'] : null;
    $shs_honor = isset($_POST['shs_honor']) ? $_POST['shs_honor'] : null;
    $vs_name = isset($_POST['vs_name']) ? $_POST['vs_name'] : null;
    $vs_type = isset($_POST['vs_type']) ? $_POST['vs_type'] : null;
    $vs_year = isset($_POST['vs_year']) ? $_POST['vs_year'] : null;
    $vs_honor = isset($_POST['vs_honor']) ? $_POST['vs_honor'] : null;
    $college_name = isset($_POST['college_name']) ? $_POST['college_name'] : null;
    $college_type = isset($_POST['college_type']) ? $_POST['college_type'] : null;
    $college_year = isset($_POST['college_year']) ? $_POST['college_year'] : null;
    $college_honor = isset($_POST['college_honor']) ? $_POST['college_honor'] : null;

    // Encrypt the data before saving it to the database
    $encrypted_gs_name = $gs_name ? encrypt($gs_name) : null;
    $encrypted_gs_type = $gs_type ? encrypt($gs_type) : null;
    $encrypted_gs_year = $gs_year ? encrypt($gs_year) : null;
    $encrypted_gs_honor = $gs_honor ? encrypt($gs_honor) : null;
    $encrypted_jhs_name = $jhs_name ? encrypt($jhs_name) : null;
    $encrypted_jhs_type = $jhs_type ? encrypt($jhs_type) : null;
    $encrypted_jhs_year = $jhs_year ? encrypt($jhs_year) : null;
    $encrypted_jhs_honor = $jhs_honor ? encrypt($jhs_honor) : null;
    $encrypted_shs_name = $shs_name ? encrypt($shs_name) : null;
    $encrypted_shs_type = $shs_type ? encrypt($shs_type) : null;
    $encrypted_shs_year = $shs_year ? encrypt($shs_year) : null;
    $encrypted_shs_honor = $shs_honor ? encrypt($shs_honor) : null;
    $encrypted_vs_name = $vs_name ? encrypt($vs_name) : null;
    $encrypted_vs_type = $vs_type ? encrypt($vs_type) : null;
    $encrypted_vs_year = $vs_year ? encrypt($vs_year) : null;
    $encrypted_vs_honor = $vs_honor ? encrypt($vs_honor) : null;
    $encrypted_college_name = $college_name ? encrypt($college_name) : null;
    $encrypted_college_type = $college_type ? encrypt($college_type) : null;
    $encrypted_college_year = $college_year ? encrypt($college_year) : null;
    $encrypted_college_honor = $college_honor ? encrypt($college_honor) : null;


    //(IV. Voluntary Work/ Athletical Affiliation - Optional for now)
    $org1_name = isset($_POST['org1_name']) ? $_POST['org1_name'] : null;
    $org1_serve = isset($_POST['org1_serve']) ? $_POST['org1_serve'] : null;
    $org1_date = isset($_POST['org1_date']) ? $_POST['org1_date'] : null;
    $org2_name = isset($_POST['org2_name']) ? $_POST['org2_name'] : null;
    $org2_serve = isset($_POST['org2_serve']) ? $_POST['org2_serve'] : null;
    $org2_date = isset($_POST['org2_date']) ? $_POST['org2_date'] : null;
    $org3_name = isset($_POST['org3_name']) ? $_POST['org3_name'] : null;
    $org3_serve = isset($_POST['org3_serve']) ? $_POST['org3_serve'] : null;
    $org3_date = isset($_POST['org3_date']) ? $_POST['org3_date'] : null;

    // Encrypt the data before saving it to the database
    $encrypted_org1_name = $org1_name ? encrypt($org1_name) : null;
    $encrypted_org1_serve = $org1_serve ? encrypt($org1_serve) : null;
    $encrypted_org1_date = $org1_date ? encrypt($org1_date) : null;
    $encrypted_org2_name = $org2_name ? encrypt($org2_name) : null;
    $encrypted_org2_serve = $org2_serve ? encrypt($org2_serve) : null;
    $encrypted_org2_date = $org2_date ? encrypt($org2_date) : null;
    $encrypted_org3_name = $org3_name ? encrypt($org3_name) : null;
    $encrypted_org3_serve = $org3_serve ? encrypt($org3_serve) : null;
    $encrypted_org3_date = $org3_date ? encrypt($org3_date) : null;

    //(V. Student’s Life Information - Optional for now)
    $marital_id = isset($_POST['marital_id']) ? $_POST['marital_id'] : null;
    $marital_others = isset($_POST['marital_others']) ? $_POST['marital_others'] : null;
    $finance_id = isset($_POST['finance_id']) ? $_POST['finance_id'] : null;
    $allowance_id = isset($_POST['allowance_id']) ? $_POST['allowance_id'] : null;
    $allowance_others = isset($_POST['allowance_others']) ? $_POST['allowance_others'] : null;
    $income_id = isset($_POST['income_id']) ? $_POST['income_id'] : null;
    $residence_id = isset($_POST['residence_id']) ? $_POST['residence_id'] : null;
    $residence_others = isset($_POST['residence_others']) ? $_POST['residence_others'] : null;

    // Encrypt the data before saving it to the database
    $encrypted_marital_others = $marital_others ? encrypt($marital_others) : null;
    $encrypted_allowance_others = $allowance_others ? encrypt($allowance_others) : null;
    $encrypted_residence_others = $residence_others ? encrypt($residence_others) : null;

    //(VI. Health Information - Optional for now)
    $dose1_name = isset($_POST['dose1_name']) ? $_POST['dose1_name'] : null;
    $dose1_date = isset($_POST['dose1_date']) ? $_POST['dose1_date'] : null;
    $dose2_name = isset($_POST['dose2_name']) ? $_POST['dose2_name'] : null;
    $dose2_date = isset($_POST['dose2_date']) ? $_POST['dose2_date'] : null;
    $boost1_name = isset($_POST['boost1_name']) ? $_POST['boost1_name'] : null;
    $boost1_date = isset($_POST['boost1_date']) ? $_POST['boost1_date'] : null;
    $boost2_name = isset($_POST['boost2_name']) ? $_POST['boost2_name'] : null;
    $boost2_date = isset($_POST['boost2_date']) ? $_POST['boost2_date'] : null;
    $vaccination = isset($_POST['vaccination']) ? $_POST['vaccination'] : null;
    $vitamin = isset($_POST['vitamin']) ? $_POST['vitamin'] : null;
    $maintenance = isset($_POST['maintenance']) ? $_POST['maintenance'] : null;
    $vision = isset($_POST['vision']) ? $_POST['vision'] : null;
    $vision_specify = isset($_POST['vision_specify']) ? $_POST['vision_specify'] : null;
    $hearing = isset($_POST['hearing']) ? $_POST['hearing'] : null;
    $hearing_specify = isset($_POST['hearing_specify']) ? $_POST['hearing_specify'] : null;
    $speech = isset($_POST['speech']) ? $_POST['speech'] : null;
    $speech_specify = isset($_POST['speech_specify']) ? $_POST['speech_specify'] : null;
    $genhealth = isset($_POST['genhealth']) ? $_POST['genhealth'] : null;
    $genhealth_specify = isset($_POST['genhealth_specify']) ? $_POST['genhealth_specify'] : null;
    $health_con = isset($_POST['health_con']) ? $_POST['health_con'] : null;
    $psychiatrist = isset($_POST['psychiatrist']) ? $_POST['psychiatrist'] : null;
    $psychiatrist_when = isset($_POST['psychiatrist_when']) ? $_POST['psychiatrist_when'] : null;
    $psychiatrist_what = isset($_POST['psychiatrist_what']) ? $_POST['psychiatrist_what'] : null;
    $psychologist = isset($_POST['psychologist']) ? $_POST['psychologist'] : null;
    $psychologist_when = isset($_POST['psychologist_when']) ? $_POST['psychologist_when'] : null;
    $psychologist_what = isset($_POST['psychologist_what']) ? $_POST['psychologist_what'] : null;
    $counselor = isset($_POST['counselor']) ? $_POST['counselor'] : null;
    $counselor_when = isset($_POST['counselor_when']) ? $_POST['counselor_when'] : null;
    $counselor_what = isset($_POST['counselor_what']) ? $_POST['counselor_what'] : null;

    // Encrypt the data before saving it to the database
    $encrypted_dose1_name = $dose1_name ? encrypt($dose1_name) : null;
    $encrypted_dose1_date = $dose1_date ? encrypt($dose1_date) : null;
    $encrypted_dose2_name = $dose2_name ? encrypt($dose2_name) : null;
    $encrypted_dose2_date = $dose2_date ? encrypt($dose2_date) : null;
    $encrypted_boost1_name = $boost1_name ? encrypt($boost1_name) : null;
    $encrypted_boost1_date = $boost1_date ? encrypt($boost1_date) : null;
    $encrypted_boost2_name = $boost2_name ? encrypt($boost2_name) : null;
    $encrypted_boost2_date = $boost2_date ? encrypt($boost2_date) : null;
    $encrypted_vaccination = $vaccination ? encrypt($vaccination) : null;
    $encrypted_vitamin = $vitamin ? encrypt($vitamin) : null;
    $encrypted_maintenance = $maintenance ? encrypt($maintenance) : null;
    $encrypted_vision = $vision ? encrypt($vision) : null;
    $encrypted_vision_specify = $vision_specify ? encrypt($vision_specify) : null;
    $encrypted_hearing = $hearing ? encrypt($hearing) : null;
    $encrypted_hearing_specify = $hearing_specify ? encrypt($hearing_specify) : null;
    $encrypted_speech = $speech ? encrypt($speech) : null;
    $encrypted_speech_specify = $speech_specify ? encrypt($speech_specify) : null;
    $encrypted_genhealth = $genhealth ? encrypt($genhealth) : null;
    $encrypted_genhealth_specify = $genhealth_specify ? encrypt($genhealth_specify) : null;
    $encrypted_health_con = $health_con ? encrypt($health_con) : null;
    $encrypted_psychiatrist = $psychiatrist ? encrypt($psychiatrist) : null;
    $encrypted_psychiatrist_when = $psychiatrist_when ? encrypt($psychiatrist_when) : null;
    $encrypted_psychiatrist_what = $psychiatrist_what ? encrypt($psychiatrist_what) : null;
    $encrypted_psychologist = $psychologist ? encrypt($psychologist) : null;
    $encrypted_psychologist_when = $psychologist_when ? encrypt($psychologist_when) : null;
    $encrypted_psychologist_what = $psychologist_what ? encrypt($psychologist_what) : null;
    $encrypted_counselor = $counselor ? encrypt($counselor) : null;
    $encrypted_counselor_when = $counselor_when ? encrypt($counselor_when) : null;
    $encrypted_counselor_what = $counselor_what ? encrypt($counselor_what) : null;

    //(VII. Interest and Hobbies - Optional for now)
    $fav_course = isset($_POST['fav_course']) ? $_POST['fav_course'] : null;
    $extra_id = isset($_POST['extra_id']) ? $_POST['extra_id'] : null;
    $extra_others = isset($_POST['extra_others']) ? $_POST['extra_others'] : null;
    $pos_id = isset($_POST['pos_id']) ? $_POST['pos_id'] : null;
    $pos_others = isset($_POST['pos_others']) ? $_POST['pos_others'] : null;
    $messenger = isset($_POST['messenger']) ? $_POST['messenger'] : null;
    $viber = isset($_POST['viber']) ? $_POST['viber'] : null;
    $comm_email = isset($_POST['comm_email']) ? $_POST['comm_email'] : null;
    $comm_other = isset($_POST['comm_other']) ? $_POST['comm_other'] : null;

    // Encrypt the data before saving it to the database
    $encrypted_fav_course = $fav_course ? encrypt($fav_course) : null;
    $encrypted_extra_others = $extra_others ? encrypt($extra_others) : null;
    $encrypted_pos_others = $pos_others ? encrypt($pos_others) : null;
    $encrypted_messenger = $messenger ? encrypt($messenger) : null;
    $encrypted_viber = $viber ? encrypt($viber) : null;
    $encrypted_comm_email = $comm_email ? encrypt($comm_email) : null;
    $encrypted_comm_other = $comm_other ? encrypt($comm_other) : null;

    // New student: require all Personal Information fields
    if (!$info_exists) {
        if (
            $campus_id && 
            $course_id && 
            $level_id && 
            $address && 
            $birthdate && 
            $birthplace &&
            $gender_id &&
            $status_id &&  
            $citizenship && 
            $height && 
            $weight &&
            $blood_id &&
            $mobile_no &&
            $tel_no
        ) {
            // Insert new record (Optional fields may be NULL)
            $insert_query = "INSERT INTO tbl_stud_info (
                student_id, 
                campus_id, 
                course_id, 
                level_id, 
                address,
                birthdate, 
                birthplace,
                gender_id,
                status_id,
                citizenship,
                height, 
                weight,
                blood_id,
                mobile_no,
                tel_no,
                father_name,
                father_occu,
                father_contact,
                mother_name,
                mother_occu,
                mother_contact,
                sib1_name,
                sib2_name,
                sib3_name,
                sib4_name,
                sib5_name,
                sib6_name,
                sib7_name,
                sib1_occu,
                sib2_occu,
                sib3_occu,
                sib4_occu,
                sib5_occu,
                sib6_occu,
                sib7_occu,
                sib1_contact,
                sib2_contact,
                sib3_contact,
                sib4_contact,
                sib5_contact,
                sib6_contact,
                sib7_contact,
                gs_name,
                gs_type,
                gs_year,
                gs_honor,
                jhs_name,
                jhs_type,
                jhs_year,
                jhs_honor,
                shs_name,
                shs_type,
                shs_year,
                shs_honor,
                vs_name,
                vs_type,
                vs_year,
                vs_honor,
                college_name,
                college_type,
                college_year,
                college_honor,
                org1_name,
                org1_serve,
                org1_date,
                org2_name,
                org2_serve,
                org2_date,
                org3_name,
                org3_serve,
                org3_date,
                marital_id,
                marital_others,
                finance_id,
                allowance_id,
                allowance_others,
                income_id,
                residence_id,
                residence_others,
                dose1_name,
                dose1_date,
                dose2_name,
                dose2_date,
                boost1_name,
                boost1_date,
                boost2_name,
                boost2_date,
                vaccination,
                vitamin,
                maintenance,
                vision,
                vision_specify,
                hearing,
                hearing_specify,
                speech,
                speech_specify,
                genhealth,
                genhealth_specify,
                health_con,
                psychiatrist,
                psychiatrist_when,
                psychiatrist_what,
                psychologist,
                psychologist_when,
                psychologist_what,
                counselor,
                counselor_when,
                counselor_what,
                fav_course,
                extra_id,
                extra_others,
                pos_id,
                pos_others,
                messenger,
                viber,
                comm_email,
                comm_other
            ) VALUES (
                '$student_id', 
                '$campus_id', 
                '$course_id', 
                '$level_id', 
                '$encrypted_address', 
                '$encrypted_birthdate', 
                '$encrypted_birthplace', 
                '$gender_id',
                '$status_id',
                '$encrypted_citizenship',
                '$encrypted_height', 
                '$encrypted_weight',
                '$blood_id',
                '$encrypted_mobile_no',
                '$encrypted_tel_no',
                " . ($encrypted_father_name ? "'$encrypted_father_name'" : "NULL") . ",
                " . ($encrypted_father_occu ? "'$encrypted_father_occu'" : "NULL") . ",
                " . ($encrypted_father_contact ? "'$encrypted_father_contact'" : "NULL") . ",
                " . ($encrypted_mother_name ? "'$encrypted_mother_name'" : "NULL") . ",
                " . ($encrypted_mother_occu ? "'$encrypted_mother_occu'" : "NULL") . ",
                " . ($encrypted_mother_contact ? "'$encrypted_mother_contact'" : "NULL") . ",
                " . ($encrypted_sib1_name ? "'$encrypted_sib1_name'" : "NULL") . ",
                " . ($encrypted_sib2_name ? "'$encrypted_sib2_name'" : "NULL") . ",
                " . ($encrypted_sib3_name ? "'$encrypted_sib3_name'" : "NULL") . ",
                " . ($encrypted_sib4_name ? "'$encrypted_sib4_name'" : "NULL") . ",
                " . ($encrypted_sib5_name ? "'$encrypted_sib5_name'" : "NULL") . ",
                " . ($encrypted_sib6_name ? "'$encrypted_sib6_name'" : "NULL") . ",
                " . ($encrypted_sib7_name ? "'$encrypted_sib7_name'" : "NULL") . ",
                " . ($encrypted_sib1_occu ? "'$encrypted_sib1_occu'" : "NULL") . ",
                " . ($encrypted_sib2_occu ? "'$encrypted_sib2_occu'" : "NULL") . ",
                " . ($encrypted_sib3_occu ? "'$encrypted_sib3_occu'" : "NULL") . ",
                " . ($encrypted_sib4_occu ? "'$encrypted_sib4_occu'" : "NULL") . ",
                " . ($encrypted_sib5_occu ? "'$encrypted_sib5_occu'" : "NULL") . ",
                " . ($encrypted_sib6_occu ? "'$encrypted_sib6_occu'" : "NULL") . ",
                " . ($encrypted_sib7_occu ? "'$encrypted_sib7_occu'" : "NULL") . ",
                " . ($encrypted_sib1_contact ? "'$encrypted_sib1_contact'" : "NULL") . ",
                " . ($encrypted_sib2_contact ? "'$encrypted_sib2_contact'" : "NULL") . ",
                " . ($encrypted_sib3_contact ? "'$encrypted_sib3_contact'" : "NULL") . ",
                " . ($encrypted_sib4_contact ? "'$encrypted_sib4_contact'" : "NULL") . ",
                " . ($encrypted_sib5_contact ? "'$encrypted_sib5_contact'" : "NULL") . ",
                " . ($encrypted_sib6_contact ? "'$encrypted_sib6_contact'" : "NULL") . ",
                " . ($encrypted_sib7_contact ? "'$encrypted_sib7_contact'" : "NULL") . ",
                " . ($encrypted_gs_name ? "'$encrypted_gs_name'" : "NULL") . ",
                " . ($encrypted_gs_type ? "'$encrypted_gs_type'" : "NULL") . ",
                " . ($encrypted_gs_year ? "'$encrypted_gs_year'" : "NULL") . ",
                " . ($encrypted_gs_honor ? "'$encrypted_gs_honor'" : "NULL") . ",
                " . ($encrypted_jhs_name ? "'$encrypted_jhs_name'" : "NULL") . ",
                " . ($encrypted_jhs_type ? "'$encrypted_jhs_type'" : "NULL") . ",
                " . ($encrypted_jhs_year ? "'$encrypted_jhs_year'" : "NULL") . ",
                " . ($encrypted_jhs_honor ? "'$encrypted_jhs_honor'" : "NULL") . ",
                " . ($encrypted_shs_name ? "'$encrypted_shs_name'" : "NULL") . ",
                " . ($encrypted_shs_type ? "'$encrypted_shs_type'" : "NULL") . ",
                " . ($encrypted_shs_year ? "'$encrypted_shs_year'" : "NULL") . ",
                " . ($encrypted_shs_honor ? "'$encrypted_shs_honor'" : "NULL") . ",
                " . ($encrypted_vs_name ? "'$encrypted_vs_name'" : "NULL") . ",
                " . ($encrypted_vs_type ? "'$encrypted_vs_type'" : "NULL") . ",
                " . ($encrypted_vs_year ? "'$encrypted_vs_year'" : "NULL") . ",
                " . ($encrypted_vs_honor ? "'$encrypted_vs_honor'" : "NULL") . ",
                " . ($encrypted_college_name ? "'$encrypted_college_name'" : "NULL") . ",
                " . ($encrypted_college_type ? "'$encrypted_college_type'" : "NULL") . ",
                " . ($encrypted_college_year ? "'$encrypted_college_year'" : "NULL") . ",
                " . ($encrypted_college_honor ? "'$encrypted_college_honor'" : "NULL") . ",
                " . ($encrypted_org1_name ? "'$encrypted_org1_name'" : "NULL") . ",
                " . ($encrypted_org1_serve ? "'$encrypted_org1_serve'" : "NULL") . ",
                " . ($encrypted_org1_date ? "'$encrypted_org1_date'" : "NULL") . ",
                " . ($encrypted_org2_name ? "'$encrypted_org2_name'" : "NULL") . ",
                " . ($encrypted_org2_serve ? "'$encrypted_org2_serve'" : "NULL") . ",
                " . ($encrypted_org2_date ? "'$encrypted_org2_date'" : "NULL") . ",
                " . ($encrypted_org3_name ? "'$encrypted_org3_name'" : "NULL") . ",
                " . ($encrypted_org3_serve ? "'$encrypted_org3_serve'" : "NULL") . ",
                " . ($encrypted_org3_date ? "'$encrypted_org3_date'" : "NULL") . ",
                " . ($marital_id ? "'$marital_id'" : "NULL") . ",
                " . ($encrypted_marital_others ? "'$encrypted_marital_others'" : "NULL") . ",
                " . ($finance_id ? "'$finance_id'" : "NULL") . ",
                " . ($allowance_id ? "'$allowance_id'" : "NULL") . ",
                " . ($encrypted_allowance_others ? "'$encrypted_allowance_others'" : "NULL") . ",
                " . ($income_id ? "'$income_id'" : "NULL") . ",
                " . ($residence_id ? "'$residence_id'" : "NULL") . ",
                " . ($encrypted_residence_others ? "'$encrypted_residence_others'" : "NULL") . ",
                " . ($encrypted_dose1_name ? "'$encrypted_dose1_name'" : "NULL") . ",
                " . ($encrypted_dose1_date ? "'$encrypted_dose1_date'" : "NULL") . ",
                " . ($encrypted_dose2_name ? "'$encrypted_dose2_name'" : "NULL") . ",
                " . ($encrypted_dose2_date ? "'$encrypted_dose2_date'" : "NULL") . ",
                " . ($encrypted_boost1_name ? "'$encrypted_boost1_name'" : "NULL") . ",
                " . ($encrypted_boost1_date ? "'$encrypted_boost1_date'" : "NULL") . ",
                " . ($encrypted_boost2_name ? "'$encrypted_boost2_name'" : "NULL") . ",
                " . ($encrypted_boost2_date ? "'$encrypted_boost2_date'" : "NULL") . ",
                " . ($encrypted_vaccination ? "'$encrypted_vaccination'" : "NULL") . ",
                " . ($encrypted_vitamin ? "'$encrypted_vitamin'" : "NULL") . ",
                " . ($encrypted_maintenance ? "'$encrypted_maintenance'" : "NULL") . ",
                " . ($encrypted_vision ? "'$encrypted_vision'" : "NULL") . ",
                " . ($encrypted_vision_specify ? "'$encrypted_vision_specify'" : "NULL") . ",
                " . ($encrypted_hearing ? "'$encrypted_hearing'" : "NULL") . ",
                " . ($encrypted_hearing_specify ? "'$encrypted_hearing_specify'" : "NULL") . ",
                " . ($encrypted_speech ? "'$encrypted_speech'" : "NULL") . ",
                " . ($encrypted_speech_specify ? "'$encrypted_speech_specify'" : "NULL") . ",
                " . ($encrypted_genhealth ? "'$encrypted_genhealth'" : "NULL") . ",
                " . ($encrypted_genhealth_specify ? "'$encrypted_genhealth_specify'" : "NULL") . ",
                " . ($encrypted_health_con ? "'$encrypted_health_con'" : "NULL") . ",
                " . ($encrypted_psychiatrist ? "'$encrypted_psychiatrist'" : "NULL") . ",
                " . ($encrypted_psychiatrist_when ? "'$encrypted_psychiatrist_when'" : "NULL") . ",
                " . ($encrypted_psychiatrist_what ? "'$encrypted_psychiatrist_what'" : "NULL") . ",
                " . ($encrypted_psychologist ? "'$encrypted_psychologist'" : "NULL") . ",
                " . ($encrypted_psychologist_when ? "'$encrypted_psychologist_when'" : "NULL") . ",
                " . ($encrypted_psychologist_what ? "'$encrypted_psychologist_what'" : "NULL") . ",
                " . ($encrypted_counselor ? "'$encrypted_counselor'" : "NULL") . ",
                " . ($encrypted_counselor_when ? "'$encrypted_counselor_when'" : "NULL") . ",
                " . ($encrypted_counselor_what ? "'$encrypted_counselor_what'" : "NULL") . ",
                " . ($encrypted_fav_course ? "'$encrypted_fav_course'" : "NULL") . ",
                " . ($extra_id ? "'$extra_id'" : "NULL") . ",
                " . ($encrypted_extra_others ? "'$encrypted_extra_others'" : "NULL") . ",
                " . ($pos_id ? "'$pos_id'" : "NULL") . ",
                " . ($encrypted_pos_others ? "'$encrypted_pos_others'" : "NULL") . ",
                " . ($encrypted_messenger ? "'$encrypted_messenger'" : "NULL") . ",
                " . ($encrypted_viber ? "'$encrypted_viber'" : "NULL") . ",
                " . ($encrypted_comm_email ? "'$encrypted_comm_email'" : "NULL") . ",
                " . ($encrypted_comm_other ? "'$encrypted_comm_other'" : "NULL") . "
            )";
            if (mysqli_query($conn, $insert_query)) {
                $_SESSION['success'] = "Profile saved successfully!";
            } else {
                $_SESSION['error'] = "Error saving profile: " . mysqli_error($conn);
            }
        } else {
            $_SESSION['error'] = "Please fill out all required fields in Personal Information.";
        }
    } else {
        // Existing student: allow optional fields for Family Background
        if (
            $campus_id && 
            $course_id && 
            $level_id && 
            $address && 
            $birthdate && 
            $birthplace && 
            $gender_id && 
            $status_id && 
            $citizenship && 
            $height && 
            $weight &&
            $blood_id &&
            $mobile_no &&
            $tel_no
        ) {
            // Update record (Optional fields can remain NULL)
            $update_query = "UPDATE tbl_stud_info 
                             SET campus_id = '$campus_id', 
                                 course_id = '$course_id', 
                                 level_id = '$level_id',
                                 address = '$encrypted_address',
                                 birthdate = '$encrypted_birthdate',
                                 birthplace = '$encrypted_birthplace',
                                 gender_id = '$gender_id',
                                 status_id = '$status_id',
                                 citizenship = '$encrypted_citizenship',
                                 height = '$encrypted_height',
                                 weight = '$encrypted_weight',
                                 blood_id = '$blood_id',
                                 mobile_no = '$encrypted_mobile_no',
                                 tel_no = '$encrypted_tel_no',
                                 father_name = " . ($encrypted_father_name ? "'$encrypted_father_name'" : "NULL") . ",
                                 father_occu = " . ($encrypted_father_occu ? "'$encrypted_father_occu'" : "NULL") . ",
                                 father_contact = " . ($encrypted_father_contact ? "'$encrypted_father_contact'" : "NULL") . ",
                                 mother_name = " . ($encrypted_mother_name ? "'$encrypted_mother_name'" : "NULL") . ",
                                 mother_occu = " . ($encrypted_mother_occu ? "'$encrypted_mother_occu'" : "NULL") . ",
                                 mother_contact = " . ($encrypted_mother_contact ? "'$encrypted_mother_contact'" : "NULL") . ",
                                 sib1_name = " . ($encrypted_sib1_name ? "'$encrypted_sib1_name'" : "NULL") . ",
                                 sib2_name = " . ($encrypted_sib2_name ? "'$encrypted_sib2_name'" : "NULL") . ",
                                 sib3_name = " . ($encrypted_sib3_name ? "'$encrypted_sib3_name'" : "NULL") . ",
                                 sib4_name = " . ($encrypted_sib4_name ? "'$encrypted_sib4_name'" : "NULL") . ",
                                 sib5_name = " . ($encrypted_sib5_name ? "'$encrypted_sib5_name'" : "NULL") . ",
                                 sib6_name = " . ($encrypted_sib6_name ? "'$encrypted_sib6_name'" : "NULL") . ",
                                 sib7_name = " . ($encrypted_sib7_name ? "'$encrypted_sib7_name'" : "NULL") . ",
                                 sib1_occu = " . ($encrypted_sib1_occu ? "'$encrypted_sib1_occu'" : "NULL") . ",
                                 sib2_occu = " . ($encrypted_sib2_occu ? "'$encrypted_sib2_occu'" : "NULL") . ",
                                 sib3_occu = " . ($encrypted_sib3_occu ? "'$encrypted_sib3_occu'" : "NULL") . ",
                                 sib4_occu = " . ($encrypted_sib4_occu ? "'$encrypted_sib4_occu'" : "NULL") . ",
                                 sib5_occu = " . ($encrypted_sib5_occu ? "'$encrypted_sib5_occu'" : "NULL") . ",
                                 sib6_occu = " . ($encrypted_sib6_occu ? "'$encrypted_sib6_occu'" : "NULL") . ",
                                 sib7_occu = " . ($encrypted_sib7_occu ? "'$encrypted_sib7_occu'" : "NULL") . ",
                                 sib1_contact = " . ($encrypted_sib1_contact ? "'$encrypted_sib1_contact'" : "NULL") . ",
                                 sib2_contact = " . ($encrypted_sib2_contact ? "'$encrypted_sib2_contact'" : "NULL") . ",
                                 sib3_contact = " . ($encrypted_sib3_contact ? "'$encrypted_sib3_contact'" : "NULL") . ",
                                 sib4_contact = " . ($encrypted_sib4_contact ? "'$encrypted_sib4_contact'" : "NULL") . ",
                                 sib5_contact = " . ($encrypted_sib5_contact ? "'$encrypted_sib5_contact'" : "NULL") . ",
                                 sib6_contact = " . ($encrypted_sib6_contact ? "'$encrypted_sib6_contact'" : "NULL") . ",
                                 sib7_contact = " . ($encrypted_sib7_contact ? "'$encrypted_sib7_contact'" : "NULL") . ",
                                 gs_name = " . ($encrypted_gs_name ? "'$encrypted_gs_name'" : "NULL") . ",
                                 gs_type = " . ($encrypted_gs_type ? "'$encrypted_gs_type'" : "NULL") . ",
                                 gs_year = " . ($encrypted_gs_year ? "'$encrypted_gs_year'" : "NULL") . ",
                                 gs_honor = " . ($encrypted_gs_honor ? "'$encrypted_gs_honor'" : "NULL") . ",
                                 jhs_name = " . ($encrypted_jhs_name ? "'$encrypted_jhs_name'" : "NULL") . ",
                                 jhs_type = " . ($encrypted_jhs_type ? "'$encrypted_jhs_type'" : "NULL") . ",
                                 jhs_year = " . ($encrypted_jhs_year ? "'$encrypted_jhs_year'" : "NULL") . ",
                                 jhs_honor = " . ($encrypted_jhs_honor ? "'$encrypted_jhs_honor'" : "NULL") . ",
                                 shs_name = " . ($encrypted_shs_name ? "'$encrypted_shs_name'" : "NULL") . ",
                                 shs_type = " . ($encrypted_shs_type ? "'$encrypted_shs_type'" : "NULL") . ",
                                 shs_year = " . ($encrypted_shs_year ? "'$encrypted_shs_year'" : "NULL") . ",
                                 shs_honor = " . ($encrypted_shs_honor ? "'$encrypted_shs_honor'" : "NULL") . ",
                                 vs_name = " . ($encrypted_vs_name ? "'$encrypted_vs_name'" : "NULL") . ",
                                 vs_type = " . ($encrypted_vs_type ? "'$encrypted_vs_type'" : "NULL") . ",
                                 vs_year = " . ($encrypted_vs_year ? "'$encrypted_vs_year'" : "NULL") . ",
                                 vs_honor = " . ($encrypted_vs_honor ? "'$encrypted_vs_honor'" : "NULL") . ",
                                 college_name = " . ($encrypted_college_name ? "'$encrypted_college_name'" : "NULL") . ",
                                 college_type = " . ($encrypted_college_type ? "'$encrypted_college_type'" : "NULL") . ",
                                 college_year = " . ($encrypted_college_year ? "'$encrypted_college_year'" : "NULL") . ",
                                 college_honor = " . ($encrypted_college_honor ? "'$encrypted_college_honor'" : "NULL") . ",
                                 org1_name = " . ($encrypted_org1_name ? "'$encrypted_org1_name'" : "NULL") . ",
                                 org1_serve = " . ($encrypted_org1_serve ? "'$encrypted_org1_serve'" : "NULL") . ",
                                 org1_date = " . ($encrypted_org1_date ? "'$encrypted_org1_date'" : "NULL") . ",
                                 org2_name = " . ($encrypted_org2_name ? "'$encrypted_org2_name'" : "NULL") . ",
                                 org2_serve = " . ($encrypted_org2_serve ? "'$encrypted_org2_serve'" : "NULL") . ",
                                 org2_date = " . ($encrypted_org2_date ? "'$encrypted_org2_date'" : "NULL") . ",
                                 org3_name = " . ($encrypted_org3_name ? "'$encrypted_org3_name'" : "NULL") . ",
                                 org3_serve = " . ($encrypted_org3_serve ? "'$encrypted_org3_serve'" : "NULL") . ",
                                 org3_date = " . ($encrypted_org3_date ? "'$encrypted_org3_date'" : "NULL") . ",
                                 marital_id = " . ($marital_id ? "'$marital_id'" : "NULL") . ",
                                 marital_others = " . ($encrypted_marital_others ? "'$encrypted_marital_others'" : "NULL") . ",
                                 finance_id = " . ($finance_id ? "'$finance_id'" : "NULL") . ",
                                 allowance_id = " . ($allowance_id ? "'$allowance_id'" : "NULL") . ",
                                 allowance_others = " . ($encrypted_allowance_others ? "'$encrypted_allowance_others'" : "NULL") . ",
                                 income_id = " . ($income_id ? "'$income_id'" : "NULL") . ",
                                 residence_id = " . ($residence_id ? "'$residence_id'" : "NULL") . ",
                                 residence_others = " . ($encrypted_residence_others ? "'$encrypted_residence_others'" : "NULL") . ",
                                 dose1_name = " . ($encrypted_dose1_name ? "'$encrypted_dose1_name'" : "NULL") . ",
                                 dose1_date = " . ($encrypted_dose1_date ? "'$encrypted_dose1_date'" : "NULL") . ",
                                 dose2_name = " . ($encrypted_dose2_name ? "'$encrypted_dose2_name'" : "NULL") . ",
                                 dose2_date = " . ($encrypted_dose2_date ? "'$encrypted_dose2_date'" : "NULL") . ",
                                 boost1_name = " . ($encrypted_boost1_name ? "'$encrypted_boost1_name'" : "NULL") . ",
                                 boost1_date = " . ($encrypted_boost1_date ? "'$encrypted_boost1_date'" : "NULL") . ",
                                 boost2_name = " . ($encrypted_boost2_name ? "'$encrypted_boost2_name'" : "NULL") . ",
                                 boost2_date = " . ($encrypted_boost2_date ? "'$encrypted_boost2_date'" : "NULL") . ",
                                 vaccination = " . ($encrypted_vaccination ? "'$encrypted_vaccination'" : "NULL") . ",
                                 vitamin = " . ($encrypted_vitamin ? "'$encrypted_vitamin'" : "NULL") . ",
                                 maintenance = " . ($encrypted_maintenance ? "'$encrypted_maintenance'" : "NULL") . ",
                                 vision = " . ($encrypted_vision ? "'$encrypted_vision'" : "NULL") . ",
                                 vision_specify = " . ($encrypted_vision_specify ? "'$encrypted_vision_specify'" : "NULL") . ",
                                 hearing = " . ($encrypted_hearing ? "'$encrypted_hearing'" : "NULL") . ",
                                 hearing_specify = " . ($encrypted_hearing_specify ? "'$encrypted_hearing_specify'" : "NULL") . ",
                                 speech = " . ($encrypted_speech ? "'$encrypted_speech'" : "NULL") . ",
                                 speech_specify = " . ($encrypted_speech_specify ? "'$encrypted_speech_specify'" : "NULL") . ",
                                 genhealth = " . ($encrypted_genhealth ? "'$encrypted_genhealth'" : "NULL") . ",
                                 genhealth_specify = " . ($encrypted_genhealth_specify ? "'$encrypted_genhealth_specify'" : "NULL") . ",
                                 health_con = " . ($encrypted_health_con ? "'$encrypted_health_con'" : "NULL") . ",
                                 psychiatrist = " . ($encrypted_psychiatrist ? "'$encrypted_psychiatrist'" : "NULL") . ",
                                 psychiatrist_when = " . ($encrypted_psychiatrist_when ? "'$encrypted_psychiatrist_when'" : "NULL") . ",
                                 psychiatrist_what = " . ($encrypted_psychiatrist_what ? "'$encrypted_psychiatrist_what'" : "NULL") . ",
                                 psychologist = " . ($encrypted_psychologist ? "'$encrypted_psychologist'" : "NULL") . ",
                                 psychologist_when = " . ($encrypted_psychologist_when ? "'$encrypted_psychologist_when'" : "NULL") . ",
                                 psychologist_what = " . ($encrypted_psychologist_what ? "'$encrypted_psychologist_what'" : "NULL") . ",
                                 counselor = " . ($encrypted_counselor ? "'$encrypted_counselor'" : "NULL") . ",
                                 counselor_when = " . ($encrypted_counselor_when ? "'$encrypted_counselor_when'" : "NULL") . ",
                                 counselor_what = " . ($encrypted_counselor_what ? "'$encrypted_counselor_what'" : "NULL") . ",
                                 fav_course = " . ($encrypted_fav_course ? "'$encrypted_fav_course'" : "NULL") . ",
                                 extra_id = " . ($extra_id ? "'$extra_id'" : "NULL") . ",
                                 extra_others = " . ($encrypted_extra_others ? "'$encrypted_extra_others'" : "NULL") . ",
                                 pos_id = " . ($pos_id ? "'$pos_id'" : "NULL") . ",
                                 pos_others = " . ($encrypted_pos_others ? "'$encrypted_pos_others'" : "NULL") . ",
                                 messenger = " . ($encrypted_messenger ? "'$encrypted_messenger'" : "NULL") . ",
                                 viber = " . ($encrypted_viber ? "'$encrypted_viber'" : "NULL") . ",
                                 comm_email = " . ($encrypted_comm_email ? "'$encrypted_comm_email'" : "NULL") . ",
                                 comm_other = " . ($encrypted_comm_other ? "'$encrypted_comm_other'" : "NULL") . "
                             WHERE student_id = '$student_id'";
            if (mysqli_query($conn, $update_query)) {
                $_SESSION['success'] = "Profile updated successfully!";
            } else {
                $_SESSION['error'] = "Error updating profile: " . mysqli_error($conn);
            }
        } else {
            $_SESSION['error'] = "All required fields must be filled in Personal Information.";
        }
    }

    header('Location: ../stud.profile.php');
    exit();
} else {
    $_SESSION['error'] = "Unauthorized access!";
    header('Location: ../stud.profile.php');
    exit();
}
