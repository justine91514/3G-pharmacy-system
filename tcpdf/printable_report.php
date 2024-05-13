<?php
// Include the TCPDF library
require_once('tcpdf/tcpdf.php');

// Establish database connection
$connection = mysqli_connect("localhost", "root", "", "dbpharmacy");

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Stocks Report');
$pdf->SetSubject('Stocks');
$pdf->SetKeywords('Stocks, Report, PDF');

// Set default header data
$pdf->SetHeaderData('', 0, 'Stocks Report', '');

// Set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Add a page
$pdf->AddPage();

// Content of the PDF (based on the existing HTML table)
$html = '<h1>Stocks Report</h1>';
$html .= '<table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>SKU</th>
                    <th>Purchase Price</th>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Branch</th>
                    <th>Expiry Date</th>
                    <th>Date Added</th>
                </tr>
            </thead>
            <tbody>';

// Fetch data from the database
$query = "SELECT * FROM add_stock_list";
$result = mysqli_query($connection, $query);

// Loop through the data and add it to the PDF table
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $html .= "<tr>";
        $html .= "<td>{$row['id']}</td>";
        $html .= "<td>{$row['sku']}</td>";
        $html .= "<td>{$row['purchase_price']}</td>";
        $html .= "<td>{$row['product_stock_name']}</td>";
        $html .= "<td>{$row['descript']}</td>";
        $html .= "<td>{$row['quantity']}</td>";
        $html .= "<td>{$row['price']}</td>";
        $html .= "<td>{$row['branch']}</td>";
        $html .= "<td>{$row['expiry_date']}</td>";
        $html .= "<td>{$row['date_added']}</td>";
        $html .= "</tr>";
    }
} else {
    $html .= "<tr><td colspan='10'>No records found</td></tr>";
}

$html .= '</tbody></table>';

// Write the HTML content into the PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Close and output PDF document
$pdf->Output('stocks_report.pdf', 'D');

// Close database connection
mysqli_close($connection);
?>
