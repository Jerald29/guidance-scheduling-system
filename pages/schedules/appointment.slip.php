<?php

require('../fpdf/fpdf.php');
require('../../includes/session.php');

// Fetch appointment details
$schedule_id = isset($_GET['schedule_id']) ? intval($_GET['schedule_id']) : 0;

$query = "SELECT 
            CONCAT(st.student_lname, ', ', st.student_fname, ' ', st.student_mname) AS full_name,
            info.course_id,
            info.level_id,
            s.appointment_date,
            s.appointment_time,
            c.course_abv,
            lev.level_name
          FROM tbl_schedules s
          LEFT JOIN tbl_students st ON s.student_id = st.student_id
          LEFT JOIN tbl_stud_info info ON st.student_id = info.student_id
          LEFT JOIN tbl_courses c ON info.course_id = c.course_id
          LEFT JOIN tbl_levels lev ON info.level_id = lev.level_id
          WHERE s.schedule_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $schedule_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die('No appointment found.');
}

$schedule = $result->fetch_assoc();

// Assign values to variables
$fullname = $schedule['full_name'];
$course_abv = $schedule['course_abv'];
$level_name = $schedule['level_name'];
$appointment_date = date("F j, Y", strtotime($schedule['appointment_date']));
$appointment_time = date("g:i A", strtotime($schedule['appointment_time']));

// Create PDF
$pdf = new FPDF('P', 'mm', 'Legal');
$pdf->SetMargins(25, 25, 26); 
$pdf->AddPage();

// Header
$pdf->Image('../../docs/assets/img/logo2.jpg', 43, 20, 19, 19);
$pdf->Image('../../docs/assets/img/sfac.png', 62, 21, 105, 18);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Ln(12);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 8, '96 Bayanan, City of Bacoor, Cavite, Philippines', 0, 1, 'C');
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Rect(161, 43, 20, 7);
$pdf->Cell(155, 0, "FORM - 12", 0, 1, 'R');
$pdf->Ln(0);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 0, 'College Department - Guidance Office', 0, 1, 'C');

$pdf->Ln(9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 8, 'GUIDANCE AND COUNSELING OFFICE APPOINTMENT/CALL SLIP', 0, 1, 'C');

$pdf->Ln(5);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'Name: ', 0, 0);
$pdf->Cell(70, 4, $fullname, 'B', 0); 

$pdf->Ln(8);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'Program: ', 0, 0);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(25, 4, $course_abv, 'B', 0); 

$pdf->Cell(-0.1, 5, '', 0, 0); // SPACE
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'Yr. Level: ', 0, 0);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(25, 4, $level_name, 'B', 0);

// Appointment Schedule Section
$pdf->Ln(15);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 8, 'APPOINTMENT SCHEDULE', 0, 1, 'C');

// Date and Time
$pdf->Ln(4);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'DATE: ', 0, 0);
$pdf->Cell(40, 4, $appointment_date, 'B', 0); // Insert Date

$pdf->Cell(70, 5, '', 0, 0); // SPACE
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'TIME: ', 0, 0);
$pdf->Cell(-1, 5, '', 0, 0); // SPACE
$pdf->Cell(30, 4, $appointment_time, 'B', 0); // Insert Time

// Instructions Section
$pdf->Ln(12);
$pdf->Cell(12, 5, 'Show this request slip to your teacher before reporting to the Guidance and Counseling Office.', 0, 0);
$pdf->Cell(1, 5, '', 0, 0); // SPACE

$pdf->Ln(8);
$pdf->Cell(12, 5, 'If for some reason, you are unable to keep this appointment due to unavoidable circumstances, ', 0, 0);
$pdf->Cell(137, 5, '', 0, 0); // SPACE
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(12, 5, 'notify the', 0, 0);
$pdf->Cell(1, 5, '', 0, 0); // SPACE

$pdf->Ln(5);
$pdf->Cell(12, 5, 'Guidance and Counseling office as soon as possible.', 0, 0);
$pdf->Cell(1, 5, '', 0, 0); // SPACE

// Counselor and Date
$pdf->Ln(14);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'COUNSELOR: ', 0, 0);
$pdf->Cell(13, 5, '', 0, 0); // SPACE
$pdf->Cell(60, 4, 'Ms. Joy-Shee Lyne C. Mendoza', 'B', 0);

$pdf->Cell(16, 5, '', 0, 0); // SPACE
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'DATE: ', 0, 0);
$pdf->Cell(-0.1, 5, '', 0, 0); // SPACE
$pdf->Cell(34, 4, $appointment_date, 'B', 0);

// Footer line for responsive paper size
$pdf->Ln(20); // Adjust the spacing from the previous content
$pdf->SetX(0); // Set X to the far left of the page

// Get the width of the page dynamically (responsive to paper size)
$pageWidth = $pdf->GetPageWidth();

// Create a full-width dashed line
$pdf->Cell($pageWidth, 5, str_repeat('-', 180), 0, 0, 'C');

// Logo(x axis, y axis, width, height)
$pdf->Image('../../docs/assets/img/logo2.jpg', 43, 184, 19, 19);
$pdf->Image('../../docs/assets/img/sfac.png', 62, 184, 105, 18);
// text color
//$pdf->SetTextColor(255, 0, 0);
// font(font type,style,font size)
$pdf->SetFont('Arial', 'B', 12);

$pdf->Ln(37);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 8, '96 Bayanan, City of Bacoor, Cavite, Philippines', 0, 1, 'C');

$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Rect(161, 206, 20, 7); // Rectangle (x, y, w, h)
$pdf->Cell(155, 0, "FORM - 12", 0, 1, 'R');

$pdf->Ln(0);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 0, 'College Department - Guidance Office', 0, 1, 'C');

$pdf->Ln(9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 8, 'GUIDANCE AND COUNSELING OFFICE APPOINTMENT/CALL SLIP', 0, 1, 'C');

$pdf->Ln(5);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'Name: ', 0, 0);
$pdf->Cell(70, 4, $fullname, 'B', 0); 

$pdf->Ln(8);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'Program: ', 0, 0);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(25, 4, $course_abv, 'B', 0); 

$pdf->Cell(-0.1, 5, '', 0, 0); // SPACE
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'Yr. Level: ', 0, 0);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(25, 4, $level_name, 'B', 0);

// Appointment Schedule Section
$pdf->Ln(15);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 8, 'APPOINTMENT SCHEDULE', 0, 1, 'C');

// Date and Time
$pdf->Ln(4);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'DATE: ', 0, 0);
$pdf->Cell(40, 4, $appointment_date, 'B', 0); // Insert Date

$pdf->Cell(70, 5, '', 0, 0); // SPACE
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'TIME: ', 0, 0);
$pdf->Cell(-1, 5, '', 0, 0); // SPACE
$pdf->Cell(30, 4, $appointment_time, 'B', 0); // Insert Time

// Instructions Section
$pdf->Ln(12);
$pdf->Cell(12, 5, 'Show this request slip to your teacher before reporting to the Guidance and Counseling Office.', 0, 0);
$pdf->Cell(1, 5, '', 0, 0); // SPACE

$pdf->Ln(8);
$pdf->Cell(12, 5, 'If for some reason, you are unable to keep this appointment due to unavoidable circumstances, ', 0, 0);
$pdf->Cell(137, 5, '', 0, 0); // SPACE
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(12, 5, 'notify the', 0, 0);
$pdf->Cell(1, 5, '', 0, 0); // SPACE

$pdf->Ln(5);
$pdf->Cell(12, 5, 'Guidance and Counseling office as soon as possible.', 0, 0);
$pdf->Cell(1, 5, '', 0, 0); // SPACE

// Counselor and Date
$pdf->Ln(14);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'COUNSELOR: ', 0, 0);
$pdf->Cell(13, 5, '', 0, 0); // SPACE
$pdf->Cell(60, 4, 'Ms. Joy-Shee Lyne C. Mendoza', 'B', 0);

$pdf->Cell(16, 5, '', 0, 0); // SPACE
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'DATE: ', 0, 0);
$pdf->Cell(-0.1, 5, '', 0, 0); // SPACE
$pdf->Cell(34, 4, $appointment_date, 'B', 0);

// Output the PDF
$pdf->Output();

?>
