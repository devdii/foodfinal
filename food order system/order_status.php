<?php
require_once 'auth.php';
include 'config.php';

// Check authentication
checkAuth();

$sessionid = session_id();
$message = '';

// Get order ID from URL or session
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : (isset($_SESSION['last_order_id']) ? $_SESSION['last_order_id'] : null);

// Show success message if order was just placed
if(isset($_GET['success']) && $_GET['success'] == 1) {
    $message = '<div class="alert alert-success">Order placed successfully! Track your order status below.</div>';
}

// Fetch all orders for the current user
$orders_query = mysqli_query($conn, "SELECT * FROM `corder` WHERE sessionid = '$sessionid' ORDER BY order_time DESC") or die('query failed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status - FoodHub</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .order-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background: white;
        }
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
        }
        .status-preparing { background: #ffd700; color: #000; }
        .status-ready { background: #87ceeb; color: #000; }
        .status-out_for_delivery { background: #ffa500; color: #000; }
        .status-delivered { background: #90ee90; color: #000; }
        
        .timeline {
            margin-top: 20px;
            position: relative;
            padding-left: 30px;
        }
        .timeline-item {
            padding: 10px 0;
            position: relative;
        }
        .timeline-item:before {
            content: '';
            position: absolute;
            left: -25px;
            top: 15px;
            width: 15px;
            height: 15px;
            border-radius: 50%;
            background: #ddd;
        }
        .timeline-item.active:before {
            background: #28a745;
        }
        .timeline-item:after {
            content: '';
            position: absolute;
            left: -19px;
            top: -10px;
            width: 2px;
            height: calc(100% + 10px);
            background: #ddd;
        }
        .timeline-item:first-child:after {
            top: 15px;
        }
        .timeline-item:last-child:after {
            height: 0;
        }
        .order-items {
            margin: 15px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .item-table {
            width: 100%;
            margin-bottom: 1rem;
        }
        .item-table th,
        .item-table td {
            padding: 8px;
            border-bottom: 1px solid #dee2e6;
        }
        .item-table th {
            background: #f1f1f1;
            font-weight: bold;
        }
        .item-table tr:last-child td {
            border-bottom: none;
        }
        .text-right {
            text-align: right;
        }
        .total-row {
            font-weight: bold;
            border-top: 2px solid #dee2e6;
            margin-top: 10px;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container my-4">
        <?php echo $message; ?>
        
        <h2 class="mb-4">Your Orders</h2>
        
        <?php 
        if(mysqli_num_rows($orders_query) > 0) {
            while($order = mysqli_fetch_assoc($orders_query)) {
                $status = $order['status'];
                $items = json_decode($order['total_products'], true);
        ?>
        <div class="order-card">
            <div class="d-flex justify-content-between align-items-center">
                <h5>Order #<?php echo $order['id']; ?></h5>
                <div>
                    <a href="generate_bill.php?order_id=<?php echo $order['id']; ?>" class="btn btn-sm btn-info mr-2" target="_blank">
                        <i class="fas fa-file-invoice"></i> View Bill
                    </a>
                    <div class="status-badge status-<?php echo $status; ?>">
                        <?php echo ucfirst(str_replace('_', ' ', $status)); ?>
                    </div>
                </div>
            </div>
            
            <div class="order-details mt-3">
                <p><strong>Ordered on:</strong> <?php echo date('d M Y, h:i A', strtotime($order['order_time'])); ?></p>
                <p><strong>Delivery to:</strong> <?php echo $order['flat'] . ', ' . $order['street'] . ', ' . $order['city'] . ' - ' . $order['pin_code']; ?></p>
                
                <div class="order-items">
                    <h6 class="mb-3">Order Items:</h6>
                    <table class="item-table">
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
                            if(is_array($items)) {
                                foreach($items as $item) {
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td class="text-right">₹<?php echo number_format($item['price'], 2); ?></td>
                                <td class="text-right"><?php echo $item['quantity']; ?></td>
                                <td class="text-right">₹<?php echo number_format($item['total'], 2); ?></td>
                            </tr>
                            <?php 
                                }
                            }
                            ?>
                            <tr class="total-row">
                                <td colspan="3" class="text-right"><strong>Total Amount:</strong></td>
                                <td class="text-right"><strong>₹<?php echo number_format($order['total_price'], 2); ?></strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <p class="mb-0"><strong>Payment Method:</strong> <?php echo $order['method']; ?></p>
            </div>

            <div class="timeline">
                <div class="timeline-item <?php echo $status == 'preparing' || $status == 'ready' || $status == 'out_for_delivery' || $status == 'delivered' ? 'active' : ''; ?>">
                    <strong>Order Preparing</strong>
                </div>
                <div class="timeline-item <?php echo $status == 'ready' || $status == 'out_for_delivery' || $status == 'delivered' ? 'active' : ''; ?>">
                    <strong>Ready for Delivery</strong>
                </div>
                <div class="timeline-item <?php echo $status == 'out_for_delivery' || $status == 'delivered' ? 'active' : ''; ?>">
                    <strong>Out for Delivery</strong>
                </div>
                <div class="timeline-item <?php echo $status == 'delivered' ? 'active' : ''; ?>">
                    <strong>Delivered</strong>
                </div>
            </div>
        </div>
        <?php 
            }
        } else {
            echo '<div class="alert alert-info">No orders found.</div>';
        }
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
