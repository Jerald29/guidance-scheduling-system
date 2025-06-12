<?php

require('../fpdf/fpdf.php');
require '../../includes/conn.php';

class PDF extends FPDF
{

    // Page header

}

$pdf = new PDF('P', 'mm', 'A4');
//left top right
$pdf->SetMargins(25, 25, 25);
$pdf->AddPage();

// Logo(x axis, y axis, width, height)
$pdf->Image('../../docs/assets/img/logo2.jpg', 43, 20, 19, 19);
$pdf->Image('../../docs/assets/img/sfac.png', 62, 21, 105, 18);
// text color
//$pdf->SetTextColor(255, 0, 0);
// font(font type,style,font size)
$pdf->SetFont('Arial', 'B', 12);

$pdf->Ln(12);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 8, '96 Bayanan, City of Bacoor, Cavite, Philippines', 0, 1, 'C');

$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Rect(161, 43, 20, 7); // Rectangle (x, y, w, h)
$pdf->Cell(155, 0, "FORM - 10", 0, 1, 'R');

$pdf->Ln(0);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 0, 'College Department - Guidance Office', 0, 1, 'C');

$pdf->Ln(9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 8, 'ENTRY AGREEMENT FORM', 0, 1, 'C');

//Cell(width , height , text , border , end line , {align} )
$pdf->Ln(5);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'Name: ', 0, 0);
$pdf->Cell(153, 4, '', 'B', 0); // FOR DATA     

$pdf->Ln(10);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'Program and level applying: ', 0, 0);
$pdf->Cell(34, 5, '', 0, 0); // SPACE                       
$pdf->Cell(45, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(10);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'Date of conference: ', 0, 0);
$pdf->Cell(21, 5, '', 0, 0); // SPACE
$pdf->Cell(45, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(12, 5, 'AGREEMENT FOR: ', 0, 0);
$pdf->Cell(30, 5, '', 0, 0); // SPACE

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(75, 5, '( ) BODY TATTOOS', 0, 0, ''); 
$pdf->Cell(-30, 5, '', 0, 0); // SPACE

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(10, 5, '( ) MENTAL HEALTH CONCERNS', 0, 0, ''); 
$pdf->Cell(14, 5, '', 0, 0); // SPACE

$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(12, 5, 'Details: ', 0, 0);
$pdf->Cell(1, 5, '', 0, 0); // SPACE

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, '(Please include the following: ', 0, 0);
$pdf->Cell(34, 5, '', 0, 0); // SPACE

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(12, 5, '1. Description of the concerned matter; 2. Behavioral', 0, 0);

$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(12, 5, 'expectations; 3. Desired results; 4. Consequences if not met.', 0, 0);
$pdf->Cell(91, 5, '', 0, 0); // SPACE

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'Attach documentations if necessary.)', 0, 0);

$pdf->Ln(6);
$pdf->Cell(165, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(6);
$pdf->Cell(165, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(6);
$pdf->Cell(165, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(6);
$pdf->Cell(165, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(6);
$pdf->Cell(165, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(6);
$pdf->Cell(165, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(6);
$pdf->Cell(165, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(8);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(12, 5, 'Proposed guidelines for body tattoos for admission in the institution: ', 0, 0);

$pdf->Ln(5.5);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, '( ) He/she is not allowed to have additional tattoos during his/her academic stay with SFAC.', 0, 0);

$pdf->Ln(5.5);
$pdf->Cell(12, 5, '( ) He/she should not encourage his/her classmates or old students to get a tattoo.', 0, 0);

$pdf->Ln(5.5);
$pdf->Cell(12, 5, '( ) He/she will wear appropriate clothes or uniform prescribed by the institution.', 0, 0);

$pdf->Ln(5.5);
$pdf->Cell(12, 5, '( ) He/she should always be at his/her best behavior inside the campus or outside as to represent SFAC.', 0, 0);

$pdf->Ln(5.5);
$pdf->Cell(12, 5, '( ) May be advised to wear blazer and/or jacket as directed by the Program Director or as necessary.', 0, 0);

$pdf->Ln(5.5);
$pdf->Cell(12, 5, '( ) Respect other people opinions and follow the rules and policies of the institution.', 0, 0);

$pdf->Ln(5.5);
$pdf->Cell(12, 5, '( ) Agrees to be on probationary (monitored) by the Guidance Counselor for the First Year of stay', 0, 0);

$pdf->Ln(5.5);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(12, 5, 'Proposed guidelines for Mental Health concerns for admission in the institution: ', 0, 0);

$pdf->Ln(5.5);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, '( ) Diagnosed by a Medical Professional (please attach medical abstract or certificate)', 0, 0);

$pdf->Ln(5.5);
$pdf->Cell(12, 5, '( ) Agrees to be monitored by the Guidance Counselor', 0, 0);

$pdf->Ln(5.5);
$pdf->Cell(12, 5, '( ) If not medically diagnosed, agrees to under go a Psychology test from a professional', 0, 0);

$pdf->Ln(5.5);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(12, 5, 'Parent and Student Agreement: ', 0, 0);

$pdf->Ln(6);
$pdf->Cell(165, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(6);
$pdf->Cell(165, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(6);
$pdf->Cell(165, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(16);
$pdf->Cell(50, 4, '', 'B', 0); // FOR DATA
$pdf->Cell(65, 5, '', 0, 0); // SPACE
$pdf->Cell(50, 4, '', 'B', 0); // FOR DATA

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(15, 5, '', 0, 0); // SPACE
$pdf->Cell(35, 5, 'STUDENT', 0, 0);
$pdf->Cell(100, 5, '', 0, 0); // SPACE
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(-27, 5, '', 0, 0); // SPACE
$pdf->Cell(10, 5, 'PARENT/GUARDIAN', 0, 0);

$pdf->Ln(5.5);
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(81, 5, '', 0, 0); // SPACE
$pdf->Cell(12, 5, 'Attested by: ', 0, 0);

$pdf->Ln(9);
$pdf->Cell(68, 4, '', 'B', 0); // FOR DATA
$pdf->Cell(29, 5, '', 0, 0); // SPACE
$pdf->Cell(68, 4, '', 'B', 0); // FOR DATA

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0.5, 5, '', 0, 0); // SPACE
$pdf->Cell(35, 5, 'GUIDANCE COUNSELOR / ADVOCATE', 0, 0);
$pdf->Cell(100, 5, '', 0, 0); // SPACE
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(-39.9, 5, '', 0, 0); // SPACE
$pdf->Cell(10, 5, 'PROGRAM DIRECTOR / COLLEGE DEAN', 0, 0);

$pdf->Ln(12);
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(59, 5, '', 0, 0); // SPACE
$pdf->Cell(12, 5, 'Noted by: ', 0, 0);

$pdf->Ln(14);
$pdf->Cell(60, 5, '', 0, 0); // SPACE
$pdf->Cell(50, 4, '', 'B', 0); // FOR DATA

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(70, 5, '', 0, 0); // SPACE
$pdf->Cell(35, 5, 'OSAS DIRECTOR', 0, 0);

// END
$pdf->Output();


?>