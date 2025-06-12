<?php
require('../fpdf/fpdf.php');

class PDF extends FPDF {
    // Page header
    function Header() {
        // Set font for the header
        $this->SetFont('Arial', 'B', 15);
        // Background color
        $this->SetFillColor(64, 224, 208); 
        // Header rectangle
        $this->Rect(0, 0, $this->GetPageWidth(), 30, 'F');
        // Title
        $this->Cell(0, 12, 'Guidance Counseling System | Privacy Policy', 0, 1, 'C', true);
        $this->Ln(10);
    }

    // Page footer
    function Footer() {
        // Go to 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    // Function to add a section with a border
    function AddSection($title, $content) {
        // Set title font and color
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(0, 51, 102);
        $this->Image('../../docs/assets/img/gcs-bac.png', 10, 4.5, 22);
        // Add title
        $this->Cell(0, 10, $title, 0, 1, 'L');
        // Add a border
        $this->SetDrawColor(0, 51, 102); // Dark blue
        $this->Rect($this->GetX() - 1, $this->GetY(), 0, 10, 'D');
        $this->Ln(2); // Small space after title
        // Set content font
        $this->SetFont('Arial', '', 12);
        $this->SetTextColor(0); // Reset to black
        // Add content
        $this->MultiCell(0, 10, $content);
        $this->Ln(5); // Space after content
    }
}

// Create instance of FPDF class
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Add sections with visuals
$pdf->AddSection("1. Information We Collect:", 
    "- Personal Identification Information: This includes your name, student ID, and other details necessary for counseling services.\n"
);

$pdf->AddSection("2. How We Use Your Information:", 
    "- To facilitate and maintain our guidance and counseling services effectively.\n"
);

$pdf->AddSection("3. Confidentiality and Data Protection:", 
    "- All personal information collected is treated with strict confidentiality and is encrypted to ensure privacy.\n"
    . "- We do not share, sell, or rent your personal information to third parties.\n"
    . "- Access to your data is limited to authorized personnel for counseling purposes only."
);

$pdf->AddSection("4. Data Security:", 
    "- We employ encryption and secure storage to protect your personal data from unauthorized access.\n"
    . "- Regular reviews and updates are conducted to ensure the security of our system and your information."
);

$pdf->AddSection("5. Changes to This Privacy Policy:", 
    "- We may update this Privacy Policy as needed to reflect changes in our counseling services.\n"
    . "- Any updates will be communicated through this page, and your continued use of our services indicates acceptance of these changes."
);

// Final consent statement
$pdf->SetFont('Arial', 'I', 12);
$pdf->SetTextColor(0); // Reset to black
$pdf->MultiCell(0, 10, "By using the Guidance Counseling System, you consent to our Privacy Policy and agree to its terms.");

// Output the PDF for viewing in the browser
$pdf->Output('I', 'Privacy_Policy.pdf');
