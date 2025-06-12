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
$pdf->Cell(155, 0, "FORM - 7", 0, 1, 'R');

$pdf->Ln(0);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 0, 'College Department - Guidance Office', 0, 1, 'C');

$pdf->Ln(9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 8, 'SESSION NOTES FORM', 0, 1, 'C');

//Cell(width , height , text , border , end line , {align} )
$pdf->Ln(8);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'Name: ', 0, 0);
$pdf->Cell(75, 4, '', 'B', 0); // FOR DATA 

$pdf->Cell(1, 5, '', 0, 0); 
$pdf->Cell(33, 5, 'Program and Level: ', 0, 0);
$pdf->Cell(43, 4, '', 'B', 0); 

$pdf->Ln(8);
$pdf->Cell(28, 5, 'Contact Number: ', 0, 0);
$pdf->Cell(25, 4, '', 'B', 0); 

$pdf->Cell(1, 5, '', 0, 0); 
$pdf->Cell(26, 5, 'E-mail Address: ', 0, 0);
$pdf->Cell(45, 4, '', 'B', 0); 

$pdf->Cell(1, 5, '', 0, 0); 
$pdf->Cell(10, 5, 'Date: ', 0, 0);
$pdf->Cell(28, 4, '', 'B', 0); 

$pdf->Ln(8);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(19, 5, 'Session #: ', 0, 0);
$pdf->Cell(35, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(15);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(19, 5, 'CONTENT: ', 0, 0);

$pdf->Ln(33);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(17, 5, 'OBSERVATION/S: ', 0, 0);

$pdf->Ln(33);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(19, 5, 'RECOMMENDATION/S: ', 0, 0);

$pdf->Ln(15);
$pdf->SetFont('Arial', '', 10); 

$pdf->Cell(85, 3, '( ) Continue session/s', 0, 0, 'C'); // Align to center
$pdf->Cell(29, 5, '', 0, 0); // SPACE

$pdf->Cell(5, 3, '( ) Terminate', 0, 0, 'C'); 
$pdf->Cell(9, 5, '', 0, 0); // SPACE

$pdf->Ln(5);
$pdf->SetFont('Arial', '', 10); 

$pdf->Cell(74, 3, '( ) For Referral', 0, 0, 'C'); 
$pdf->Cell(29, 5, '', 0, 0); // SPACE

$pdf->Cell(24, 3, '( ) Others: ', 0, 0, 'C'); 
$pdf->Cell(-4, 5, '', 0, 0); // SPACE
$pdf->Cell(30, 4, '', 'B', 0); // FOR DATA

$pdf->Ln(15);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(19, 5, 'Prepared by: ', 0, 0);

$pdf->Ln(15);
$pdf->Cell(69, 4, '', 'B', 0); // FOR DATA

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
$pdf->Cell(69, 4, '', 'B', 0); // FOR DATA

$pdf->Ln(5);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(19, 5, 'College Dean', 0, 0);

$pdf->Ln(5);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(19, 5, 'Signature Over Printed Name/ Date', 0, 0);

$pdf->Output();
?>