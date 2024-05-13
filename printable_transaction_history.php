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
        $this->Cell(0, 90, 'Transaction History Report', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        
        // Move Y position below the text
        $this->SetY(100); // Adjust the Y position based on your requirements
    }

    //Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

// create new PDF document
$pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Transaction History Report');
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

// Fetch data from the database
$connection = mysqli_connect("localhost", "root", "", "dbpharmacy");

// Retrieve filter parameters from URL
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : null;
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : null;
$branch = isset($_GET['branch']) ? $_GET['branch'] : '';
$cashier_name = isset($_GET['cashier_name']) ? $_GET['cashier_name'] : '';

// Adjusted SQL query based on filter parameters
$query = "SELECT transaction_id, date, CONCAT(DATE_FORMAT(time, '%h:%i:%s'), DATE_FORMAT(NOW(), '%p')) AS time_with_am_pm, transaction_no, mode_of_payment, ref_no, list_of_items, sub_total, total_amount, cashier_name, branch FROM transaction_list WHERE 1=1";

$params = [];
if (!empty($from_date) && !empty($to_date)) {
    $query .= " AND date BETWEEN ? AND ?";
    $params[] = $from_date;
    $params[] = $to_date;
}

if (!empty($branch)) {
    $query .= " AND branch = ?";
    $params[] = $branch;
}

if (!empty($cashier_name)) {
    $query .= " AND cashier_name = ?";
    $params[] = $cashier_name;
}

$stmt = mysqli_prepare($connection, $query);
if (!empty($params)) {
    $typeStr = str_repeat('s', count($params));
    mysqli_stmt_bind_param($stmt, $typeStr, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Add a page
$pdf->AddPage();

// Set font for the table headers
$pdf->SetFont('helvetica', 'B', 11);

// Set fill color for header row
$pdf->SetFillColor(37, 158, 158);
$pdf->SetTextColor(255);

// Header row
$pdf->Cell(30, 10, 'Transaction No.', 1, 0, 'C', 1);
$pdf->Cell(30, 10, 'Date', 1, 0, 'C', 1);
$pdf->Cell(30, 10, 'Time', 1, 0, 'C', 1);
$pdf->Cell(30, 10, 'Mode of Payment', 1, 0, 'C', 1);
$pdf->Cell(30, 10, 'Reference No.', 1, 0, 'C', 1);
$pdf->Cell(30, 10, 'List of Items', 1, 0, 'C', 1);
$pdf->Cell(20, 10, 'Sub Total', 1, 0, 'C', 1);
$pdf->Cell(20, 10, 'Grand Total', 1, 0, 'C', 1);
$pdf->Cell(30, 10, 'Cashier Name', 1, 0, 'C', 1);
$pdf->Cell(30, 10, 'Branch', 1, 1, 'C', 1);

// Set font for the table data
$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(0);

// Data rows
while ($row = mysqli_fetch_assoc($result)) {
    $pdf->Cell(30, 10, $row['transaction_no'], 1, 0, 'C');
    $pdf->Cell(30, 10, $row['date'], 1, 0, 'C');
    $pdf->Cell(30, 10, $row['time_with_am_pm'], 1, 0, 'C');
    $pdf->Cell(30, 10, $row['mode_of_payment'], 1, 0, 'C');
    $pdf->Cell(30, 10, $row['ref_no'], 1, 0, 'C');
    $pdf->Cell(30, 10, $row['list_of_items'], 1, 0, 'C');
    $pdf->Cell(20, 10, $row['sub_total'], 1, 0, 'C');
    $pdf->Cell(20, 10, $row['total_amount'], 1, 0, 'C');
    $pdf->Cell(30, 10, $row['cashier_name'], 1, 0, 'C');
    $pdf->Cell(30, 10, $row['branch'], 1, 1, 'C');
}

// Close database connection
mysqli_close($connection);

// Close and output PDF document
$pdf->Output('transaction_history_report.pdf', 'I');
?>
