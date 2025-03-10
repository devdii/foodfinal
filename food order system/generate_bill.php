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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill - Order #<?php echo $order_id; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: #f8f9fa;
            padding: 20px;
        }
        .bill-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .bill-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .bill-details {
            margin-bottom: 30px;
        }
        .table th {
            background: #f8f9fa;
        }
        .total-row {
            font-weight: bold;
            background: #f8f9fa;
        }
        .text-right {
            text-align: right;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .bill-container {
                box-shadow: none;
                padding: 15px;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="bill-container">
        <div class="bill-header">
            <h2>FoodHub</h2>
            <h4>Order Bill</h4>
        </div>

        <div class="bill-details">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Order ID:</strong> #<?php echo $order['id']; ?></p>
                    <p><strong>Order Date:</strong> <?php echo date('d M Y, h:i A', strtotime($order['order_time'])); ?></p>
                    <p><strong>Customer Name:</strong> <?php echo $order['name']; ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Delivery Address:</strong><br>
                    <?php echo $order['flat'] . ', ' . $order['street'] . ',<br>' . $order['city'] . ' - ' . $order['pin_code']; ?></p>
                    <p><strong>Phone:</strong> <?php echo $order['number']; ?></p>
                </div>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Quantity</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $items = json_decode($order['total_products'], true);
                if(is_array($items)) {
                    foreach($items as $item) {
                        $item_total = $item['price'] * $item['quantity'];
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td class="text-right">Rs <?php echo number_format($item['price'], 2); ?></td>
                    <td class="text-right"><?php echo $item['quantity']; ?></td>
                    <td class="text-right">Rs <?php echo number_format($item_total, 2); ?></td>
                </tr>
                <?php
                    }
                }
                ?>
                <tr class="total-row">
                    <td colspan="3" class="text-right"><strong>Total Amount:</strong></td>
                    <td class="text-right"><strong>Rs <?php echo number_format($order['total_price'], 2); ?></strong></td>
                </tr>
            </tbody>
        </table>

        <div class="row mt-4">
            <div class="col-md-6">
                <p><strong>Payment Method:</strong> <?php echo $order['method']; ?></p>
            </div>
            <div class="col-md-6 text-right">
                <p class="mb-0">Thank you for ordering with FoodHub!</p>
            </div>
        </div>

        <div class="mt-4 text-center no-print">
            <button onclick="window.print()" class="btn btn-primary">Print Bill</button>
            <a href="download_bill.php?order_id=<?php echo $order_id; ?>" class="btn btn-success">Download PDF</a>
            <a href="order_status.php" class="btn btn-secondary">Back to Orders</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<?php
    }
}
?>
