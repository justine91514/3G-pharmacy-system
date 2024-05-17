<?php
require_once('tcpdf/tcpdf.php');

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = K_PATH_IMAGES.'3GMED.jpg';
        
        // Calculate X position to center the image
        $pageWidth = $this->getPageWidth();
        $imageWidth = 100; // Adjust as needed
        $xPos = ($pageWidth - $imageWidth) / 2;

        $this->Image($image_file, $xPos, 10, 100, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        
        // Calculate X positions to center the line
        $lineStartX = $xPos - 90;
        $lineEndX = $xPos + 190; // Adjust line length as needed

        // Draw a line below the logo
        $this->Line($lineStartX, 75, $lineEndX, 75); // Adjust Y position to move the line below the logo

        // Set font
        $this->SetFont('times', 'B', 30);
        
        // Add Inventory Reports text
        $this->SetY(40); // Adjust the Y position as needed
        $this->Cell(0, 90, 'Product Report', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        
        // Move Y position below the text
        $this->SetY(100); // Adjust the Y position based on your requirements
    }

//Page footer
public function Footer() {
    // Set Y position at 15 mm from bottom
    $this->SetY(-15);
    // Set font
    $this->SetFont('helvetica', 'I', 8);
    $this->Cell(0, 10, 'Generated by: Admin ', 0, false, 'L', 0, '', 0, false, 'T', 'M');
    // Set timezone to Asia/Manila
    date_default_timezone_set('Asia/Manila');
    // Right side: Date and time in Philippine time format
    $this->Cell(0, 10, 'Generated on: ' . date('Y-m-d h:i A'), 0, false, 'R', 0, '', 0, false, 'T', 'M');
}
}

// create new PDF document
$pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Product Report');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 100, PDF_MARGIN_RIGHT); // Increased top margin to accommodate the header

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------
// Fetch data from the database based on selected branch
$selectedBranch = isset($_GET['branch']) ? $_GET['branch'] : 'All';

$connection = mysqli_connect("localhost", "root", "", "dbpharmacy");
$query = "SELECT * FROM product_list ORDER BY id DESC"; // Modified query to sort by ID in descending order
$result = mysqli_query($connection, $query);


// Calculate the maximum width needed for each column
$maxWidth = array(20, 30, 30, 30, 45, 35, 40, 35); // Default widths for ID, Product Name, Category, Type, Measurement, Stocks Available, Prescription, and Has Discount

while ($row = mysqli_fetch_assoc($result)) {
    // Update max width for each column based on the content
    $maxWidth[0] = max($maxWidth[0], $pdf->GetStringWidth($row['id']) + 5); // Add some padding
    $maxWidth[1] = max($maxWidth[1], $pdf->GetStringWidth($row['prod_name']) + 5);
    $maxWidth[2] = max($maxWidth[2], $pdf->GetStringWidth($row['categories']) + 5);
    $maxWidth[3] = max($maxWidth[3], $pdf->GetStringWidth($row['type']) + 5);
    $maxWidth[4] = max($maxWidth[4], $pdf->GetStringWidth($row['unit']) + 5);
    $maxWidth[6] = max($maxWidth[5], $pdf->GetStringWidth($row['stocks_available']) + 5);
    $maxWidth[7] = max($maxWidth[6], $pdf->GetStringWidth(($row['prescription'] == 1 ? 'Yes' : 'No')) + 5);

}

// Add a page
$pdf->AddPage();

// Set font for the table headers
$pdf->SetFont('helvetica', 'B', 11);

// Set fill color for header row
$pdf->SetFillColor(37, 158, 158); // Change the fill color to #259E9E
$pdf->SetTextColor(255);

// Calculate X position to center the table headers
$headerXPos = ($pdf->getPageWidth() - array_sum($maxWidth)) / 2;

$pdf->SetX($headerXPos); // Set X position to center the table headers

// Header row
$pdf->Cell($maxWidth[0], 10, 'ID', 1, 0, 'C', 1);
$pdf->Cell($maxWidth[1], 10, 'Product Name', 1, 0, 'C', 1);
$pdf->Cell($maxWidth[2], 10, 'Category', 1, 0, 'C', 1);
$pdf->Cell($maxWidth[3], 10, 'Type', 1, 0, 'C', 1);
$pdf->Cell($maxWidth[4], 10, 'Unit', 1, 0, 'C', 1);
$pdf->Cell($maxWidth[5], 10, 'Stocks Available', 1, 0, 'C', 1);
$pdf->Cell($maxWidth[6], 10, 'Prescription', 1, 1, 'C', 1);


// Set font for the table data
$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(0);

$pdf->SetX($headerXPos); // Set X position to center the table data

// Data rows
mysqli_data_seek($result, 0); // Resetting the result pointer
while ($row = mysqli_fetch_assoc($result)) {
    $pdf->Cell($maxWidth[0], 10, $row['id'], 1, 0, 'C');
    $pdf->Cell($maxWidth[1], 10, $row['prod_name'], 1, 0, 'C');
    $pdf->Cell($maxWidth[2], 10, $row['categories'], 1, 0, 'C');
    $pdf->Cell($maxWidth[3], 10, $row['type'], 1, 0, 'C');
    $pdf->Cell($maxWidth[4], 10, $row['unit'], 1, 0, 'C');
    $pdf->Cell($maxWidth[5], 10, $row['stocks_available'], 1, 0, 'C');
    $pdf->Cell($maxWidth[6], 10, ($row['prescription'] == 1 ? 'Yes' : 'No'), 1, 1, 'C');
    $pdf->SetX($headerXPos); // Set X position to center the table data
}

// Close database connection
mysqli_close($connection);

// Close and output PDF document
$pdf->Output('user_management_report.pdf', 'I');
?>
