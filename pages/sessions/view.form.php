<?php

require('../fpdf/fpdf.php');
require '../../includes/conn.php';
require_once '../../includes/encryption.php';

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Render the header only on the first page
        if ($this->PageNo() == 1) {
            $this->Image('../../docs/assets/img/logo2.jpg', 43, 20, 19, 19);
            $this->Image('../../docs/assets/img/sfac.png', 62, 21, 105, 18);

            $this->SetFont('Arial', 'B', 12);

            $this->Ln(12);
            $this->SetFont('Arial', '', 10);
            $this->Cell(0, 8, '96 Bayanan, City of Bacoor, Cavite, Philippines', 0, 1, 'C');

            $this->Ln(2);
            $this->SetFont('Arial', 'B', 9);
            $this->Rect(162, 43, 20, 7); // Rectangle (x, y, w, h)
            $this->Cell(152, 0, "FORM - 7", 0, 1, 'R');

            $this->Ln(0);
            $this->SetFont('Arial', '', 10);
            $this->Cell(0, 0, 'College Department - Guidance Office', 0, 1, 'C');
        }
    }
}

$pdf = new PDF('P', 'mm', 'Legal');
// Set margins (left, top, right)
$pdf->SetMargins(28, 25, 25);
$pdf->AddPage();

// Get session_id
$session_id = $_GET['session_id'];

// Fetch session data with recommendations
$query = "SELECT 
            CONCAT(stud.student_lname, ', ', stud.student_fname, ' ', stud.student_mname) AS full_name,
            cou.course_abv,
            lev.level_abv,
            info.mobile_no,
            stud.email,
            sf.session_date,
            sf.session_number,
            sf.content,
            sf.observation,
            reco.reco_id, reco.reco_name,
            sf.session_others
          FROM tbl_session_forms sf
          JOIN tbl_students stud ON sf.student_id = stud.student_id
          JOIN tbl_stud_info info ON stud.student_id = info.student_id
          JOIN tbl_courses cou ON info.course_id = cou.course_id
          JOIN tbl_levels lev ON info.level_id = lev.level_id
          JOIN tbl_recommendations reco ON sf.reco_id = reco.reco_id 
          WHERE sf.session_id = ?";

// Prepare and execute the query
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $session_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Get values
$full_name = $row['full_name'];
$program_level = $row['course_abv'] . ' - ' . $row['level_abv'];
$email = $row['email'];
$session_date = date('M j, Y', strtotime($row['session_date']));
$session_number = $row['session_number'];
$content = $row['content'];
$observation = $row['observation'];
$reco_id = $row['reco_id'];
$session_others = $row['session_others'];

// For checkbox
$checkMark = '/';
$unchecked = ' ';

$continueSession = ($reco_id == 1) ? $checkMark : $unchecked;
$forReferral = ($reco_id == 2) ? $checkMark : $unchecked;
$terminate = ($reco_id == 3) ? $checkMark : $unchecked;
$others = ($reco_id == 4) ? $checkMark : $unchecked;

// Decrypt
$decrypted_mobile_no = $row['mobile_no'] ? decrypt($row['mobile_no']) : null;

$pdf->Ln(9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 8, 'SESSION NOTES FORM', 0, 1, 'C');

$pdf->Ln(8);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'Name: ', 0, 0);
$pdf->Cell(75, 4, $full_name, 'B', 0); 

$pdf->Cell(1, 5, '', 0, 0); 
$pdf->Cell(33, 5, 'Program and Level: ', 0, 0);
$pdf->Cell(43, 4, $program_level, 'B', 0);

$pdf->Ln(8);
$pdf->Cell(28, 5, 'Contact Number: ', 0, 0);
$pdf->Cell(25, 4, $decrypted_mobile_no, 'B', 0);  

$pdf->Cell(1, 5, '', 0, 0); 
$pdf->Cell(26, 5, 'E-mail Address: ', 0, 0);
$pdf->Cell(50, 4, $email, 'B', 0); 

$pdf->Cell(1, 5, '', 0, 0); 
$pdf->Cell(10, 5, 'Date: ', 0, 0);
$pdf->Cell(23, 4, $session_date, 'B', 0); 

$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(19, 5, 'Session #: ', 0, 0);
$pdf->Cell(4, 5, $session_number, '', 0);

$pdf->Ln(12);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(19, 5, 'CONTENT: ', 0, 0);
$pdf->Ln(6);
$pdf->SetFont('Arial', '', 10);
$pdf->SetX(29);
$pdf->MultiCell(163, 5, $content, 0, 'J');

$pdf->Ln(8);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(19, 5, 'OBSERVATION/S: ', 0, 0);
$pdf->Ln(6);
$pdf->SetFont('Arial', '', 10);
$pdf->SetX(29);
$pdf->MultiCell(163, 5, $observation, 0, 'J');

$pdf->Ln(8);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(19, 5, 'RECOMMENDATION/S: ', 0, 0);

$pdf->Ln(10);
$pdf->SetFont('Arial', '', 10);

$pdf->Cell(85, 5, "($continueSession) Continue session/s", 0, 0, 'C');
$pdf->Cell(29, 5, '', 0, 0);
$pdf->Cell(5, 5, "($terminate) Terminate", 0, 0, 'C');

$pdf->Ln(5);
$pdf->Cell(74, 5, "($forReferral) For Referral", 0, 0, 'C');
$pdf->Cell(29, 5, '', 0, 0);
$pdf->Cell(24, 5, "($others) Others: ", 0, 0, 'C');
$pdf->Cell(-4, 5, '', 0, 0);
$pdf->Cell(38, 4, $session_others, 'B', 0);

$pdf->Ln(15);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(19, 5, 'Prepared by: ', 0, 0);

$pdf->Ln(15);
$pdf->Cell(69, 4, '', 'B', 0);

$pdf->Ln(5);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(19, 5, 'Guidance Officer', 0, 0);

$pdf->Ln(5);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(19, 5, 'Signature Over Printed Name/ Date', 0, 0);

$pdf->Ln(15);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(19, 5, 'Noted by: ', 0, 0);

$pdf->Ln(15);
$pdf->Cell(69, 4, '', 'B', 0);
$pdf->Ln(5);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(19, 5, 'College Dean', 0, 0);

$pdf->Ln(5);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(19, 5, 'Signature Over Printed Name/ Date', 0, 0);

// Output the PDF
$pdf->Output();

?>