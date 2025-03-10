<?php
require_once 'auth.php';
include 'config.php';
require_once __DIR__ . '/vendor/tecnickcom/tcpdf/tcpdf.php';

// Check authentication
checkAuth();

if(isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $order_query = mysqli_query($conn, "SELECT * FROM `corder` WHERE id = '$order_id'") or die('query failed');
    
    if(mysqli_num_rows($order_query) > 0) {
        $order = mysqli_fetch_assoc($order_query);
        
        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Set document information
        $pdf->SetCreator('FoodHub');
        $pdf->SetAuthor('FoodHub');
        $pdf->SetTitle('Order Bill #' . $order_id);
        
        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // Set margins
        $pdf->SetMargins(15, 15, 15);
        
        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 15);
        
        // Add a page
        $pdf->AddPage();
        
        // Set font
        $pdf->SetFont('helvetica', 'B', 16);
        
        // Title
        $pdf->Cell(0, 10, 'FoodHub', 0, 1, 'C');
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'Order Bill', 0, 1, 'C');
        
        // Order details
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Ln(5);
        
        // Customer Details
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(40, 8, 'Order ID:', 0, 0);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 8, '#' . $order['id'], 0, 1);
        
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(40, 8, 'Date:', 0, 0);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 8, date('d M Y, h:i A', strtotime($order['order_time'])), 0, 1);
        
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(40, 8, 'Name:', 0, 0);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 8, $order['name'], 0, 1);
        
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(40, 8, 'Address:', 0, 0);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->MultiCell(0, 8, $order['flat'] . ', ' . $order['street'] . ', ' . $order['city'] . ' - ' . $order['pin_code'], 0, 'L');
        
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(40, 8, 'Phone:', 0, 0);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 8, $order['number'], 0, 1);
        
        $pdf->Ln(5);
        
        // Items Table Header
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->SetFillColor(245, 245, 245);
        $pdf->Cell(80, 10, 'Item', 1, 0, 'L', true);
        $pdf->Cell(35, 10, 'Price', 1, 0, 'R', true);
        $pdf->Cell(30, 10, 'Qty', 1, 0, 'R', true);
        $pdf->Cell(35, 10, 'Total', 1, 1, 'R', true);
        
        // Items
        $pdf->SetFont('helvetica', '', 12);
        $items = json_decode($order['total_products'], true);
        $total = 0;
        
        if(is_array($items)) {
            foreach($items as $item) {
                $item_total = $item['price'] * $item['quantity'];
                $total += $item_total;
                
                $pdf->Cell(80, 10, $item['name'], 1, 0, 'L');
                $pdf->Cell(35, 10, 'Rs ' . number_format($item['price'], 2), 1, 0, 'R');
                $pdf->Cell(30, 10, $item['quantity'], 1, 0, 'R');
                $pdf->Cell(35, 10, 'Rs ' . number_format($item_total, 2), 1, 1, 'R');
            }
        }
        
        // Total
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(145, 10, 'Total Amount:', 1, 0, 'R', true);
        $pdf->Cell(35, 10, 'Rs ' . number_format($order['total_price'], 2), 1, 1, 'R', true);
        
        // Payment Method
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(40, 8, 'Payment:', 0, 0);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 8, $order['method'], 0, 1);
        
        // Footer
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 10, 'Thank you for ordering with FoodHub!', 0, 1, 'C');
        
        // Output PDF
        $pdf->Output('FoodHub_Order_' . $order_id . '.pdf', 'D');
    }
}
?>
