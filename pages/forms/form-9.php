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
$pdf->Rect(162, 43, 20, 7); // Rectangle (x, y, w, h)
$pdf->Cell(155, 0, "FORM - 9", 0, 1, 'R');

$pdf->Ln(0);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 0, 'College Department - Guidance Office', 0, 1, 'C');

$pdf->Ln(9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 8, 'CONFERENCE LETTER', 0, 1, 'C');

$pdf->Ln(5);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(30, 5, '', 0, 0); // SPACE
$pdf->Cell(75, 5, '[ ] First Notice', 0, 0, ''); 
$pdf->Cell(-36, 5, '', 0, 0); // SPACE

$pdf->Cell(10, 5, '[ ] Second Notice', 0, 0, ''); 
$pdf->Cell(33, 5, '', 0, 0); // SPACE

$pdf->Cell(-10, 5, '[ ] Third Notice', 0, 0, ''); 
$pdf->Cell(28, 5, '', 0, 0); // SPACE

//Cell(width , height , text , border , end line , {align} )
$pdf->Ln(11);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(10, 5, 'Date: ', 0, 0);
$pdf->Cell(40, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(11);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(9, 5, 'Dear', 0, 0);
$pdf->Cell(40, 4, '', 'B', 0); // FOR DATA 
$pdf->Cell(5, 5, ',', 0, 0);

$pdf->Ln(11);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(10, 5, 'Greetings!', 0, 0);

$pdf->Ln(11);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(10, 5, 'We would like to have a conference with you regarding your child\'s: ', 0, 0);

$pdf->Ln(6);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(30, 5, '', 0, 0); // SPACE
$pdf->Cell(75, 5, '[ ] Academics', 0, 0, ''); 
$pdf->Cell(-36, 5, '', 0, 0); // SPACE

$pdf->Cell(10, 5, '[ ] Discipline', 0, 0, ''); 
$pdf->Cell(33, 5, '', 0, 0); // SPACE

$pdf->Cell(-10, 5, '[ ] Other: ', 0, 0, ''); 
$pdf->Cell(28, 5, '', 0, 0); // SPACE
$pdf->Cell(36, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(6);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(10, 5, 'Please see the following individual/s below on', 0, 0);
$pdf->Cell(63, 5, '', 0, 0); // SPACE
$pdf->Cell(40, 4, '', 'B', 0); // FOR DATA 
$pdf->Cell(10, 5, 'at', 0, 0);
$pdf->Cell(-5, 5, '', 0, 0); // SPACE
$pdf->Cell(40, 4, '', 'B', 0); // FOR DATA 

$pageWidth = 210;
$leftMargin = 25; 
$availableWidth = $pageWidth - ($leftMargin * 2); 
$boxWidth = $availableWidth / 2;
$offset = 4; 

// Set y-coordinate for each row
$yPosition1 = 130;
$yPosition2 = 136;
$yPosition3 = 142;

// First row of boxes
$pdf->Rect($leftMargin + $offset, $yPosition1, $boxWidth, 6);
$pdf->SetXY($leftMargin + $offset, $yPosition1);
$pdf->Cell($boxWidth, 6, 'Course', 0, 0, 'C');

$pdf->Rect($leftMargin + $boxWidth + $offset, $yPosition1, $boxWidth, 6);
$pdf->SetXY($leftMargin + $boxWidth + $offset, $yPosition1);
$pdf->Cell($boxWidth, 6, 'Instructor', 0, 0, 'C');

// Second row of boxes
$pdf->Rect($leftMargin + $offset, $yPosition2, $boxWidth, 6);
$pdf->Rect($leftMargin + $boxWidth + $offset, $yPosition2, $boxWidth, 6);

// Third row of boxes
$pdf->Rect($leftMargin + $offset, $yPosition3, $boxWidth, 6);
$pdf->Rect($leftMargin + $boxWidth + $offset, $yPosition3, $boxWidth, 6);

$pdf->Ln(24);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(10, 5, 'Others: ', 0, 0);

$pdf->Ln(6);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(20, 5, '', 0, 0); // SPACE
$pdf->Cell(70, 5, '[ ] Guidance Counselor', 0, 0, ''); 
$pdf->Cell(-8, 5, '', 0, 0); // SPACE
$pdf->Cell(70, 5, '[ ] College Dean', 0, 0, ''); 

$pdf->Ln(6);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(20, 5, '', 0, 0); // SPACE
$pdf->Cell(70, 5, '[ ] Discipline Officer', 0, 0, ''); 
$pdf->Cell(-8, 5, '', 0, 0); // SPACE
$pdf->Cell(70, 5, '[ ] OSAS', 0, 0, ''); 

$pdf->Ln(6);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(20, 5, '', 0, 0); // SPACE
$pdf->Cell(70, 5, '[ ] Program Director', 0, 0, ''); 

$pdf->Ln(11);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(10, 5, 'Your utmost cooperation in this matter is highly demanded.', 0, 0);

$pdf->Ln(11);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(10, 5, 'Respectfully yours,', 0, 0);
$pdf->Cell(100, 5, '', 0, 0); // SPACE
$pdf->Cell(10, 5, 'Noted by:', 0, 0);
$pdf->Ln(7);
$pdf->Cell(53, 4, '', 'B', 0); // FOR DATA 
$pdf->Cell(57, 5, '', 0, 0); // SPACE
$pdf->Cell(53, 4, '', 'B', 0); // FOR DATA 



// END
$pdf->Output();


?>