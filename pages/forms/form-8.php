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
$pdf->Cell(155, 0, "FORM - 8", 0, 1, 'R');

$pdf->Ln(0);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 0, 'College Department - Guidance Office', 0, 1, 'C');

//Cell(width , height , text , border , end line , {align} )
$pdf->Ln(10);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(26, 5, 'Date Received: ', 0, 0);
$pdf->Cell(45, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 11);

// Calculate the X position for centering the text
$pageWidth = $pdf->GetPageWidth();
$textWidth = $pdf->GetStringWidth('GUIDANCE AND COUNSELING REFERRAL FORM');
$x = ($pageWidth - $textWidth) / 3.5;

// Set the X position and print the text
$pdf->SetX($x);
$pdf->Cell(0, 8, 'GUIDANCE AND COUNSELING REFERRAL FORM', 0, 1, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'Name: ', 0, 0);
$pdf->Cell(75, 4, '', 'B', 0); // FOR DATA 

$pdf->Cell(1, 5, '', 0, 0); 
$pdf->Cell(33, 5, 'Program and Level: ', 0, 0);
$pdf->Cell(45, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(8);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(43, 5, 'Parent/ Guardian\'s Name: ', 0, 0);
$pdf->Cell(65, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(6);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(29, 5, 'Contact Number: ', 0, 0);
$pdf->Cell(65, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(9);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(29, 5, 'Referred by: ', 0, 0);

$pdf->Cell(75, 5, '( ) Teacher', 0, 0, ''); 
$pdf->Cell(-50, 5, '', 0, 0); // SPACE

$pdf->Cell(10, 5, '( ) Parent', 0, 0, ''); 
$pdf->Cell(14, 5, '', 0, 0); // SPACE

$pdf->Cell(-10, 5, '( ) Self', 0, 0, ''); 
$pdf->Cell(28, 5, '', 0, 0); // SPACE

$pdf->Cell(20, 5, '( ) Others: ', 0, 0, 'C'); 
$pdf->Cell(-1, 5, '', 0, 0); // SPACE
$pdf->Cell(51, 4, '', 'B', 0); // FOR DATA

$pdf->Ln(6);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(22, 5, 'Date of Birth: ', 0, 0);
$pdf->Cell(50, 4, '', 'B', 0); // FOR DATA 

$pdf->Cell(1, 5, '', 0, 0); 
$pdf->Cell(30, 5, 'Student lives with: ', 0, 0);
$pdf->Cell(63, 4, '', 'B', 0); 

$pdf->Ln(10);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'Reason/s for referral-problems/ concerns related to: (Clarify Referral problem/ history)', 0, 0);

$pdf->Ln(6);
$pdf->Cell(166, 4, '', 'B', 0); // FOR DATA 
$pdf->Ln(6);
$pdf->Cell(166, 4, '', 'B', 0); // FOR DATA 
$pdf->Ln(6);
$pdf->Cell(166, 4, '', 'B', 0); // FOR DATA 
$pdf->Ln(6);
$pdf->Cell(166, 4, '', 'B', 0); // FOR DATA 
$pdf->Ln(6);
$pdf->Cell(166, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(10);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'ACTIONS taken by the person referring this student/s, if applicable, please attach copies of any', 0, 0);
$pdf->Ln(5);
$pdf->Cell(12, 5, 'intervention/s attempted: ', 0, 0);

$pdf->Ln(6);
$pdf->Cell(166, 4, '', 'B', 0); // FOR DATA 
$pdf->Ln(6);
$pdf->Cell(166, 4, '', 'B', 0); // FOR DATA 
$pdf->Ln(6);
$pdf->Cell(166, 4, '', 'B', 0); // FOR DATA 
$pdf->Ln(6);
$pdf->Cell(166, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(10);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(29, 5, 'Have you contacted parent/ guardian about your concern: ', 0, 0);
$pdf->Cell(63, 5, '', 0, 0); // SPACE

$pdf->Cell(75, 5, '( ) NO', 0, 0, ''); 
$pdf->Cell(-60, 5, '', 0, 0); // SPACE

$pdf->Cell(10, 5, '( ) YES,', 0, 0, ''); 
$pdf->Cell(1, 5, '', 0, 0); // SPACE

$pdf->Cell(20, 5, 'Date: ', 0, 0, 'C'); 
$pdf->Cell(-5, 5, '', 0, 0); // SPACE
$pdf->Cell(33, 4, '', 'B', 0); // FOR DATA

$pdf->Ln(10);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'Explain below the outcome of parent contact/conversation: ', 0, 0);

$pdf->Ln(6);
$pdf->Cell(166, 4, '', 'B', 0); // FOR DATA 
$pdf->Ln(6);
$pdf->Cell(166, 4, '', 'B', 0); // FOR DATA 
$pdf->Ln(6);
$pdf->Cell(166, 4, '', 'B', 0); // FOR DATA 
$pdf->Ln(6);
$pdf->Cell(166, 4, '', 'B', 0); // FOR DATA 
$pdf->Ln(6);
$pdf->Cell(166, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'What other services does the student is currently/ or receiving (out of school counseling, etc.): ', 0, 0);

$pdf->Ln(6);
$pdf->Cell(166, 4, '', 'B', 0); // FOR DATA 
$pdf->Ln(6);
$pdf->Cell(166, 4, '', 'B', 0); // FOR DATA 
$pdf->Ln(6);
$pdf->Cell(166, 4, '', 'B', 0); // FOR DATA 
$pdf->Ln(6);
$pdf->Cell(166, 4, '', 'B', 0); // FOR DATA 
$pdf->Ln(6);
$pdf->Cell(166, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(22);
$pdf->Cell(76, 4, '', 'B', 0); // FOR DATA
$pdf->Cell(25, 5, '', 0, 0); // SPACE
$pdf->Cell(50, 4, '', 'B', 0); // FOR DATA

$pdf->Ln(5);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'Name and Signature of Person Making Referral', 0, 0);
$pdf->Cell(100, 5, '', 0, 0); // SPACE
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'Date of Referral', 0, 0);

$pdf->Ln(26);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(12, 5, 'PRIORITY:', 0, 0);

$pdf->Ln(10);
$pdf->Cell(14, 5, '', 0, 0); // SPACE
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(10, 5, '( ) Low (schedule when available)', 0, 0, ''); 
$pdf->Cell(-60, 5, '', 0, 0); // SPACE

$pdf->Ln(5);
$pdf->Cell(14, 5, '', 0, 0); // SPACE
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(10, 5, '( ) High (schedule as soon as possible)', 0, 0, ''); 

$pdf->Ln(5);
$pdf->Cell(14, 5, '', 0, 0); // SPACE
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(10, 5, '( ) Emergency (see now)', 0, 0, '');   

$pdf->Ln(22);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(12, 5, 'Below is for the School Counseling Office Only:', 0, 0);

$pdf->Ln(10);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(29, 5, 'Initial Date seen by the counselor: ', 0, 0);
$pdf->Cell(26, 5, '', 0, 0); // SPACE
$pdf->Cell(65, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(10);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(29, 5, 'Name of Counselor: ', 0, 0);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(65, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(10);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(29, 5, 'Best time to counsel student: ', 0, 0);
$pdf->Cell(18, 5, '', 0, 0); // SPACE
$pdf->Cell(115, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(10);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(29, 5, 'Follow-up session date: ', 0, 0);
$pdf->Cell(10, 5, '', 0, 0); // SPACE
$pdf->Cell(123, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(10);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(29, 5, 'Additional Note/s: ', 0, 0);

// END
$pdf->Output();


?>