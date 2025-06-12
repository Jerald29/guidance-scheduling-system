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
$pdf->Cell(155, 0, "FORM - 6", 0, 1, 'R');

$pdf->Ln(0);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 0, 'College Department - Guidance Office', 0, 1, 'C');

$pdf->Ln(9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 8, 'NO HARM SAFETY CONTRACT FORM', 0, 1, 'C');

$pdf->Ln(6);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(-5, 5, '', 0, 0); // SPACE
$pdf->Cell(12, 5, 'I, the undersigned, agree that I will not do anything that would cause harm to myself and/or to anyone', 0, 0);

$pdf->Ln(6);
$pdf->Cell(-5, 5, '', 0, 0); // SPACE
$pdf->Cell(12, 5, 'else. I realize that I am responsible for my own actions and that if I feel my life is becoming too difficult,', 0, 0);

$pdf->Ln(6);
$pdf->Cell(-5, 5, '', 0, 0); // SPACE
$pdf->Cell(12, 5, 'I agree to do one or more of the following safety actions/interventions so that no harm will be inflicted', 0, 0);

$pdf->Ln(6);
$pdf->Cell(-5, 5, '', 0, 0); // SPACE
$pdf->Cell(12, 5, 'on myself or others:', 0, 0);

$pdf->Ln(10);
$pdf->Cell(-5, 5, '', 0, 0); // SPACE
$pdf->Cell(12, 5, '1. Talk with a family member or friend, such as (list names and phone number): ', 0, 0);

$pdf->Ln(60);
$pdf->Cell(-5, 5, '', 0, 0); // SPACE
$pdf->Cell(12, 5, '2. Do something I typically enjoy, even though I may not find it enjoyable at the same time (list', 0, 0);

$pdf->Ln(5);
$pdf->Cell(-5, 5, '', 0, 0); // SPACE
$pdf->Cell(12, 5, 'some of those activities):', 0, 0);

$pdf->Ln(60);
$pdf->Cell(-5, 5, '', 0, 0); // SPACE
$pdf->Cell(12, 5, '3. Other safety actions that I agree to do are: ', 0, 0);

$pdf->Output();


?>  