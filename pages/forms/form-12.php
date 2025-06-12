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
$pdf->Cell(155, 0, "FORM - 12", 0, 1, 'R');

$pdf->Ln(0);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 0, 'College Department - Guidance Office', 0, 1, 'C');

$pdf->Ln(9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 8, 'GUIDANCE AND COUNSELING OFFICE APPOINTMENT/CALL SLIP', 0, 1, 'C');

//Cell(width , height , text , border , end line , {align} )
$pdf->Ln(5);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'Name: ', 0, 0);
$pdf->Cell(90, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(8);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'Program: ', 0, 0);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(50, 4, '', 'B', 0); // FOR DATA 

$pdf->Cell(-0.1, 5, '', 0, 0); // SPACE
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'Yr. Level: ', 0, 0);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(25, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 8, 'APPOINTMENT SCHEDULE', 0, 1, 'C');

$pdf->Ln(3.5);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'DATE: ', 0, 0);
$pdf->Cell(40, 4, '', 'B', 0); // FOR DATA 

$pdf->Cell(70, 5, '', 0, 0); // SPACE
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'TIME: ', 0, 0);
$pdf->Cell(-1, 5, '', 0, 0); // SPACE
$pdf->Cell(30, 4, '', 'B', 0); // FOR DATA 

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

$pdf->Ln(14);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'COUNSELOR: ', 0, 0);
$pdf->Cell(13, 5, '', 0, 0); // SPACE
$pdf->Cell(80, 4, '', 'B', 0); // FOR DATA 

$pdf->Cell(16, 5, '', 0, 0); // SPACE
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'DATE: ', 0, 0);
$pdf->Cell(-0.1, 5, '', 0, 0); // SPACE
$pdf->Cell(30, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(20);
$pdf->Cell(12, 5, '-------------------------------------------------------------------------------------------------------------------------------------------', 0, 0);

// Logo(x axis, y axis, width, height)
$pdf->Image('../../docs/assets/img/logo2.jpg', 43, 178, 19, 19);
$pdf->Image('../../docs/assets/img/sfac.png', 62, 178, 105, 18);
// text color
//$pdf->SetTextColor(255, 0, 0);
// font(font type,style,font size)
$pdf->SetFont('Arial', 'B', 12);

$pdf->Ln(38);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 8, '96 Bayanan, City of Bacoor, Cavite, Philippines', 0, 1, 'C');

$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Rect(161, 201, 20, 7); // Rectangle (x, y, w, h)
$pdf->Cell(155, 0, "FORM - 12", 0, 1, 'R');

$pdf->Ln(0);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 0, 'College Department - Guidance Office', 0, 1, 'C');

$pdf->Ln(9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 8, 'GUIDANCE AND COUNSELING OFFICE APPOINTMENT/CALL SLIP', 0, 1, 'C');

//Cell(width , height , text , border , end line , {align} )
$pdf->Ln(5);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'Name: ', 0, 0);
$pdf->Cell(90, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(8);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'Program: ', 0, 0);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(50, 4, '', 'B', 0); // FOR DATA 

$pdf->Cell(-0.1, 5, '', 0, 0); // SPACE
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'Yr. Level: ', 0, 0);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(25, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 8, 'APPOINTMENT SCHEDULE', 0, 1, 'C');

$pdf->Ln(3.5);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'DATE: ', 0, 0);
$pdf->Cell(40, 4, '', 'B', 0); // FOR DATA 

$pdf->Cell(70, 5, '', 0, 0); // SPACE
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'TIME: ', 0, 0);
$pdf->Cell(-1, 5, '', 0, 0); // SPACE
$pdf->Cell(30, 4, '', 'B', 0); // FOR DATA 

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

$pdf->Ln(14);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'COUNSELOR: ', 0, 0);
$pdf->Cell(13, 5, '', 0, 0); // SPACE
$pdf->Cell(80, 4, '', 'B', 0); // FOR DATA 

$pdf->Cell(16, 5, '', 0, 0); // SPACE
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(12, 5, 'DATE: ', 0, 0);
$pdf->Cell(-0.1, 5, '', 0, 0); // SPACE
$pdf->Cell(30, 4, '', 'B', 0); // FOR DATA 

// END
$pdf->Output();


?>  