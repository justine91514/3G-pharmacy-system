<?php
require_once('tcpdf/tcpdf.php');

// Initialize DB connection
$connection = mysqli_connect("localhost", "root", "", "dbpharmacy");

if ($connection) {
    if (isset($_POST['print_id'])) {
        $transaction_id = mysqli_real_escape_string($connection, $_POST['print_id']);
        $query = "SELECT transaction_id, date, CONCAT(DATE_FORMAT(time, '%h:%i:%s'), DATE_FORMAT(NOW(), '%p')) AS time_with_am_pm, transaction_no, mode_of_payment, ref_no, list_of_items, quantity, sub_total, total_amount, SUBSTRING_INDEX(cashier_name, ' ', 1) AS first_name, branch FROM transaction_list WHERE transaction_id = $transaction_id";
        $query_run = mysqli_query($connection, $query);

        if ($query_run && mysqli_num_rows($query_run) > 0) {
            $row = mysqli_fetch_assoc($query_run);
            // Define the page size, 80mm wide by 200mm high (change height as necessary)
            $pageWidth = 80;
            $pageHeight = 200;
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, 'mm', array($pageWidth, $pageHeight), true, 'UTF-8', false);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetTitle('Receipt for Transaction No. ' . $row['transaction_no']);
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetMargins(5, 5, 5);  // Tighter margins
            $pdf->AddPage();
            // Set path for the image
            $image_file = K_PATH_IMAGES.'3GMED.jpg';

            // Use monospaced font
            $pdf->SetFont('courier', '', 10);

            // Start building HTML content
            $html = <<<EOD
            <style>
            h1 { font-size: 12pt; font-weight: bold; text-align: center; margin: 0; }
            .header, .footer { text-align: center; font-size: 8pt; margin: 0; }
            .info { font-size: 8pt; margin: 0; }
            .items { font-size: 8pt; margin: 0; }
            .items th, .items td { border-bottom: 1px solid #ccc; padding: 2px; }
            th { background-color: #eee; }
            .dashed { border-top: 1px dashed #000; margin: 0; padding: 0; }
            </style>
            <div class="header">
            <img src="$image_file" width="40mm" height="30mm">
            <p>12 Songcuan Bldg. Rainbow Village ML Quezon Ave. Brgy. San Isidro Angono, Rizal</p>
            <p>Phone: 09989234524</p>
            <p class="info"><strong>Date and Time:</strong> {$row['date']} {$row['time_with_am_pm']}</p>
            <p class="info"><strong>Transaction No:</strong> {$row['transaction_no']}  <strong>Cashier:</strong> {$row['first_name']}</p>
            <p class="info"><strong>Branch:</strong> {$row['branch']}</p>
            <div class="dashed"></div>
            <p class="info"><strong>Item/s:</strong>{$row['list_of_items']}</p>
            <div class="dashed"></div>
            <p class="info"><strong>Sub Total:</strong> Php.{$row['sub_total']}</p>
            <p class="info"><strong>Total Amount:</strong> Php.{$row['total_amount']}</p>
            <div class="footer">
            <p>THIS IS AN OFFICIAL RECEIPT!</p>
            </div>
            </div>    
            EOD;

            // Write HTML content to PDF
            $pdf->writeHTML($html, true, false, true, false, '');
            // Output PDF
            $pdf->Output('receipt.pdf', 'I');
        } else {
            echo "No transaction found.";
        }
    } else {
        echo "Invalid request.";
    }

    // Close the database connection
    mysqli_close($connection);
} else {
    echo "Failed to connect to the database.";
}
?>
