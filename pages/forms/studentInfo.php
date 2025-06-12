<?php

require('../fpdf/fpdf.php');
require '../../includes/conn.php';

class PDF extends FPDF
{
    // Page header
}

$pdf = new PDF('P', 'mm', array(104, 178));
$pdf->SetMargins(3, 3, 3);
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 8); // Adjust font size to fit within the page width

$pdf->Ln(1);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 10, 'STUDENT INFORMATION SHEET', 0, 1, 'C'); // Center aligned

//Cell(width , height , text , border , end line , {align} )
$pdf->Ln(1);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(25, 5, 'EMAIL FOR SOA: ', 0, 0);
$pdf->Cell(64, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(15, 5, 'GENDER: ', 0, 0);
$pdf->Cell(7, 4, '', 'B', 0); // FOR DATA 

$pdf->Cell(1, 5, '', 0, 0); // SPACE
$pdf->Cell(16, 5, 'OLD/NEW: ', 0, 0);
$pdf->Cell(7, 4, '', 'B', 0); // FOR DATA 

$pdf->Cell(1, 5, '', 0, 0); // SPACE
$pdf->Cell(12, 5, 'LEVEL: ', 0, 0);
$pdf->Cell(7, 4, '', 'B', 0); // FOR DATA 

$pdf->Cell(1, 5, '', 0, 0); // SPACE
$pdf->Cell(14, 5, 'STRAND: ', 0, 0);
$pdf->Cell(9, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(22, 5, 'FAMILY NAME: ', 0, 0);
$pdf->Cell(44, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(21, 5, 'GIVEN NAME: ', 0, 0);
$pdf->Cell(45, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(22, 5, 'MIDDLE NAME: ', 0, 0);
$pdf->Cell(44, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(16, 5, 'ADDRESS: ', 0, 0);
$pdf->Cell(73, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(1, 5, '', 0, 0);
$pdf->Cell(88, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(19, 5, 'BIRTH DATE: ', 0, 0);
$pdf->Cell(33, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(33, 5, 'STUDENT MOBILE NO.: ', 0, 0);
$pdf->Cell(33, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(16, 5, 'RELIGION: ', 0, 0);
$pdf->Cell(50, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(21, 5, 'NATIONALITY: ', 0, 0);
$pdf->Cell(45, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(21, 5, 'BIRTH PLACE: ', 0, 0);
$pdf->Cell(68, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(33, 5, 'SCH. LAST ATTENDED: ', 0, 0);
$pdf->Cell(56, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(16, 5, 'ADDRESS: ', 0, 0);
$pdf->Cell(73, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(28, 5, 'DATE GRADUATED: ', 0, 0);
$pdf->Cell(38, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(27, 5, 'NAME OF FATHER: ', 0, 0);
$pdf->Cell(62, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(22, 5, 'CONTACT NO.: ', 0, 0);
$pdf->Cell(44, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(28, 5, 'NAME OF MOTHER: ', 0, 0);
$pdf->Cell(61, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(22, 5, 'CONTACT NO.: ', 0, 0);
$pdf->Cell(44, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(24, 5, 'FATHER OCCU.: ', 0, 0);
$pdf->Cell(42, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(25, 5, 'MOTHER OCCU.: ', 0, 0);
$pdf->Cell(41, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(6);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(0, 10, 'PERSON TO CONTACT IN CASE OF EMERGENCY', 0, 1, 'C'); // Center aligned

$pdf->Ln(1);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(10, 5, 'NAME: ', 0, 0);
$pdf->Cell(79, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(16, 5, 'ADDRESS: ', 0, 0);
$pdf->Cell(73, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(20, 5, 'TEL/CEL NO.: ', 0, 0);
$pdf->Cell(69, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(27, 5, 'OFFICE ADDRESS: ', 0, 0);
$pdf->Cell(62, 4, '', 'B', 0); // FOR DATA 

$pdf->Ln(5);
$pdf->Cell(4, 5, '', 0, 0); // SPACE
$pdf->Cell(33, 5, 'OFFICE CONTACT NO.: ', 0, 0);
$pdf->Cell(56, 4, '', 'B', 0); // FOR DATA 

$pdf->Output();
?>