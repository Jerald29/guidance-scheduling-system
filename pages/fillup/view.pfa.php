<?php
require('../fpdf/fpdf.php');
require '../../includes/conn.php';
require_once '../../includes/encryption.php';

// Get the student ID from the query string or any other way you're passing it
$student_id = $_GET['student_id'];  // assuming you are passing it via the URL

// Fetch student and PFA data
$query = "SELECT 
            CONCAT(stud.student_lname, ', ', stud.student_fname, ' ', stud.student_mname) AS full_name,
            c.course_abv, lev.level_name, info.mobile_no, stud.email,
            pfa.pfa_date, pfa.pfa_age, pfa.ref_id, pfa.ref_other, pfa.nos_id, pfa.problem_discuss, pfa.counselor_name
          FROM tbl_students stud
          JOIN tbl_stud_info info ON stud.student_id = info.student_id
          JOIN tbl_courses c ON info.course_id = c.course_id
          JOIN tbl_levels lev ON info.level_id = lev.level_id
          LEFT JOIN tbl_pfa_forms pfa ON stud.student_id = pfa.student_id
          WHERE stud.student_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    die("No data found for the student. Kindly ensure that your Student Profile Form is filled");
}

class PDF extends FPDF
{
    // Page header
}

$pdf = new PDF('P', 'mm', 'A4');
$pdf->SetMargins(25, 25, 25);
$pdf->AddPage();

// Logo (x axis, y axis, width, height)
$pdf->Image('../../docs/assets/img/logo2.jpg', 43, 20, 19, 19);
$pdf->Image('../../docs/assets/img/sfac.png', 62, 21, 105, 18);

// Set fonts and styles
$pdf->SetFont('Arial', 'B', 12);
$pdf->Ln(12);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 8, '96 Bayanan, City of Bacoor, Cavite, Philippines', 0, 1, 'C');
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Rect(162, 43, 20, 7);  // Rectangle (x, y, w, h)
$pdf->Cell(155, 0, "FORM - 4", 0, 1, 'R');
$pdf->Ln(0);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 0, 'College Department - Guidance Office', 0, 1, 'C');
$pdf->Ln(9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 8, 'PSYCHOLOGICAL FIRST AID FORM', 0, 1, 'C');

// Student Information
$pdf->Ln(8);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'Name: ', 0, 0);
$pdf->Cell(75, 4, $data['full_name'], 'B', 0);

$pdf->Cell(1, 5, '', 0, 0); 
$pdf->Cell(33, 5, 'Program and Level: ', 0, 0);
$pdf->Cell(43, 4, $data['course_abv'] . ' - ' . $data['level_name'], 'B', 0);


// Decrypt mobile number
$mobile_no = decrypt($data['mobile_no']);

$pdf->Ln(8);
$pdf->Cell(28, 5, 'Contact Number: ', 0, 0);
$pdf->Cell(25, 4, $mobile_no, 'B', 0);

$pdf->Cell(1, 5, '', 0, 0); 
$pdf->Cell(26, 5, 'E-mail Address: ', 0, 0);
$pdf->Cell(45, 4, $data['email'], 'B', 0); 
$pdf->Cell(1, 5, '', 0, 0); 
$pdf->Cell(10, 5, 'Date: ', 0, 0);
$session_date = date('M j, Y', strtotime($data['pfa_date'])); // Abbreviated month, day without leading zero, and year
$pdf->Cell(28, 4, $session_date, 'B', 0);

$pdf->Ln(15);
$pdf->Cell(9, 5, 'Age: ', 0, 0);
$pdf->Cell(15, 4, $data['pfa_age'], 'B', 0);

$pdf->Ln(10);
$pdf->Cell(30, 3, 'Referred by: ', 0, 0);

$pdf->Cell(5, 3, ($data['ref_id'] == 1) ? '(/)' : '( )', '', 0);
$pdf->Cell(6, 3, 'Teacher/Instructor', 0, 0);
$pdf->Cell(29, 5, '', 0, 0);

$pdf->Cell(5, 3, ($data['ref_id'] == 2) ? '(/)' : '( )', '', 0);
$pdf->Cell(6, 3, 'Parent', 0, 0);
$pdf->Cell(11, 5, '', 0, 0);

$pdf->Cell(5, 3, ($data['ref_id'] == 3) ? '(/)' : '( )', '', 0);
$pdf->Cell(6, 3, 'Discipline Officer', 0, 0);
$pdf->Cell(25, 5, '', 0, 0);

$pdf->Cell(5, 3, ($data['ref_id'] == 4) ? '(/)' : '( )', '', 0); 
$pdf->Cell(6, 3, 'Self', 0, 1);

$pdf->Ln(6);
$pdf->Cell(30, 5, '', 0, 0); // SPACE
$pdf->Cell(5, 3, ($data['ref_id'] == 5) ? '(/)' : '( )', '', 0);
$pdf->Cell(12, 3, 'Other: ', 0, 0);
$pdf->Cell(25, 3, $data['ref_other'], 'B', 0);

$pdf->Ln(10);
$pdf->Cell(30, 3, 'Number of Session/s attended: ', 0, 0);

$pdf->Ln(10);
$pdf->Cell(13, 5, '', 0, 0); // SPACE
$pdf->Cell(5, 3, ($data['nos_id'] == 1) ? '(/)' : '( )', '', 0);
$pdf->Cell(6, 3, 'First', 0, 0);
$pdf->Cell(15, 5, '', 0, 0);

$pdf->Cell(5, 3, ($data['nos_id'] == 2) ? '(/)' : '( )', '', 0);
$pdf->Cell(6, 3, 'Second', 0, 0);
$pdf->Cell(20, 5, '', 0, 0);

$pdf->Cell(5, 3, ($data['nos_id'] == 3) ? '(/)' : '( )', '', 0);
$pdf->Cell(6, 3, 'Third', 0, 0);
$pdf->Cell(16, 5, '', 0, 0);

$pdf->Cell(5, 3, ($data['nos_id'] == 4) ? '(/)' : '( )', '', 0);
$pdf->Cell(6, 3, 'Fourth', 0, 0);
$pdf->Cell(18, 5, '', 0, 0);

$pdf->Cell(5, 3, ($data['nos_id'] == 5) ? '(/)' : '( )', '', 0);
$pdf->Cell(6, 3, 'Fifth', 0, 1);

$pdf->Ln(9);
$pdf->Cell(30, 3, 'Problem/s to be discussed: ', 0, 0);
$pdf->Ln(7);
$pdf->Cell(1, 5, '', 0, 0);
$pdf->MultiCell(163, 4, $data['problem_discuss'], 0, 'J'); 


$pdf->Ln(35);
$pdf->Cell(33, 5, 'Student\'s signature: ', 0, 0);
$pdf->Cell(75, 4, '', 'B', 0);

$pdf->Ln(9);
$pdf->Cell(33, 5, 'Name of Counselor: ', 0, 0);
$pdf->Cell(75, 4, $data['counselor_name'], 'B', 0); 

$pdf->Output();
?>
