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
$pdf->Cell(155, 0, "FORM - 4", 0, 1, 'R');

$pdf->Ln(0);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 0, 'College Department - Guidance Office', 0, 1, 'C');

$pdf->Ln(9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 8, 'PSYCHOLOGICAL FIRST AID FORM', 0, 1, 'C');

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

$pdf->Ln(15);
$pdf->Cell(9, 5, 'Age: ', 0, 0);
$pdf->Cell(15, 4, '', 'B', 0); 

$pdf->Ln(10);
$pdf->Cell(30, 3, 'Refered by: ', 0, 0);

$pdf->Cell(5, 3, '( )', '', 0); 
$pdf->Cell(6, 3, 'Teacher/Instructor', 0, 0); // Checkbox
$pdf->Cell(29, 5, '', 0, 0); // SPACE

$pdf->Cell(5, 3, '( )', '', 0); 
$pdf->Cell(6, 3, 'Parent', 0, 0); // Checkbox
$pdf->Cell(11, 5, '', 0, 0); // SPACE

$pdf->Cell(5, 3, '( )', '', 0); 
$pdf->Cell(6, 3, 'Discipline Officer', 0, 0); // Checkbox
$pdf->Cell(25, 5, '', 0, 0); // SPACE

$pdf->Cell(5, 3, '( )', '', 0); 
$pdf->Cell(6, 3, 'Self', 0, 1); // Checkbox

$pdf->Ln(6);
$pdf->Cell(30, 5, '', 0, 0); // SPACE
$pdf->Cell(5, 3, '( )', '', 0); 
$pdf->Cell(12, 3, 'Other: ', 0, 0); // Checkbox
$pdf->Cell(25, 3, '', 'B', 0);

$pdf->Ln(10);
$pdf->Cell(30, 3, 'Number of Session/s attended: ', 0, 0);

$pdf->Ln(10);
$pdf->Cell(13, 5, '', 0, 0); // SPACE
$pdf->Cell(5, 3, '( )', '', 0); 
$pdf->Cell(6, 3, 'First', 0, 0); // Checkbox
$pdf->Cell(15, 5, '', 0, 0); // SPACE

$pdf->Cell(5, 3, '( )', '', 0); 
$pdf->Cell(6, 3, 'Second', 0, 0); // Checkbox
$pdf->Cell(20, 5, '', 0, 0); // SPACE

$pdf->Cell(5, 3, '( )', '', 0); 
$pdf->Cell(6, 3, 'Third', 0, 0); // Checkbox
$pdf->Cell(16, 5, '', 0, 0); // SPACE

$pdf->Cell(5, 3, '( )', '', 0); 
$pdf->Cell(6, 3, 'Fourth', 0, 0); // Checkbox
$pdf->Cell(18, 5, '', 0, 0); // SPACE

$pdf->Cell(5, 3, '( )', '', 0); 
$pdf->Cell(6, 3, 'Fifth', 0, 1); // Checkbox

$pdf->Ln(9);
$pdf->Cell(30, 3, 'Problem/s to be discussed: ', 0, 0);

$pdf->Ln(7);
$pdf->Cell(1, 5, '', 0, 0);
$pdf->Cell(163, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(1, 5, '', 0, 0);
$pdf->Cell(163, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(1, 5, '', 0, 0);
$pdf->Cell(163, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(1, 5, '', 0, 0);
$pdf->Cell(163, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(1, 5, '', 0, 0);
$pdf->Cell(163, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(1, 5, '', 0, 0);
$pdf->Cell(163, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(1, 5, '', 0, 0);
$pdf->Cell(163, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(1, 5, '', 0, 0);
$pdf->Cell(163, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(1, 5, '', 0, 0);
$pdf->Cell(163, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(1, 5, '', 0, 0);
$pdf->Cell(163, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(1, 5, '', 0, 0);
$pdf->Cell(163, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(1, 5, '', 0, 0);
$pdf->Cell(163, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(1, 5, '', 0, 0);
$pdf->Cell(163, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(1, 5, '', 0, 0);
$pdf->Cell(163, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(1, 5, '', 0, 0);
$pdf->Cell(163, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(35);
$pdf->Cell(33, 5, 'Student\'s signature: ', 0, 0);
$pdf->Cell(75, 4, '', 'B', 0); 

$pdf->Ln(9);
$pdf->Cell(33, 5, 'Name of Counselor: ', 0, 0);
$pdf->Cell(75, 4, '', 'B', 0); 

$pdf->Output();
?>
