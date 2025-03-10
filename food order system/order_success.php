<?php
require_once 'auth.php';

// Check authentication and session timeout
checkAuth();
if (checkSessionTimeout()) {
    header('Location: login.php?msg=session_expired');
    exit();
}

@include 'config.php';

// Get the latest order for this session
$sessionid = session_id();
$order_query = mysqli_query($conn, "SELECT * FROM `corder` WHERE sessionid = '$sessionid' ORDER BY id DESC LIMIT 1");
$order_data = mysqli_fetch_assoc($order_query);

if (!$order_data) {
    header('Location: products.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success - FoodHub</title>
    <link rel="shortcut icon" type="image/png" href="images/icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            padding-top: 80px;
        }
        .success-container {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin-bottom: 30px;
            text-align: center;
        }
        .success-icon {
            color: #2ecc71;
            font-size: 5em;
            margin-bottom: 20px;
        }
        .success-heading {
            color: #2d3436;
            font-weight: 600;
            margin-bottom: 30px;
        }
        .order-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: left;
        }
        .order-details h4 {
            color: #2d3436;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .order-details p {
            margin-bottom: 10px;
            color: #636e72;
        }
        .order-details strong {
            color: #2d3436;
        }
        .total-amount {
            font-size: 1.3em;
            color: #e67e22;
            font-weight: 600;
            margin: 20px 0;
        }
        .continue-shopping {
            background: linear-gradient(45deg, #FF416C, #FF4B2B);
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            transition: all 0.3s;
        }
        .continue-shopping:hover {
            background: linear-gradient(45deg, #FF4B2B, #FF416C);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 65, 108, 0.3);
            color: white;
            text-decoration: none;
        }
        .products-list {
            margin: 15px 0;
            padding: 0;
            list-style: none;
        }
        .products-list li {
            padding: 5px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .products-list li:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <div class="success-container">
            <i class="fas fa-check-circle success-icon"></i>
            <h1 class="success-heading">Thank You for Your Order!</h1>
            <p class="lead">Your order has been successfully placed and will be processed soon.</p>

            <div class="order-details">
                <h4>Order Details</h4>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($order_data['name']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($order_data['number']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($order_data['email']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order_data['method']); ?></p>
                        <p><strong>Delivery Address:</strong><br>
                            <?php echo htmlspecialchars($order_data['flat']) . ', ' . 
                                     htmlspecialchars($order_data['street']) . ',<br>' . 
                                     htmlspecialchars($order_data['city']) . ' - ' . 
                                     htmlspecialchars($order_data['pin_code']); ?>
                        </p>
                    </div>
                </div>

                <h4 class="mt-4">Ordered Items</h4>
                <ul class="products-list">
                    <?php 
                    $products = explode(', ', $order_data['total_products']);
                    foreach($products as $product) {
                        echo '<li>' . htmlspecialchars($product) . '</li>';
                    }
                    ?>
                </ul>

                <div class="total-amount text-right">
                    Total Amount: ₹<?php echo number_format($order_data['total_price']); ?>/-
                </div>
            </div>

            <?php if($order_data['method'] == 'cash on delivery'): ?>
            <div class="alert alert-info" role="alert">
                <i class="fas fa-info-circle mr-2"></i>
                Payment will be collected upon delivery
            </div>
            <?php endif; ?>

            <a href="products.php" class="continue-shopping">
                <i class="fas fa-arrow-left mr-2"></i>Continue Shopping
            </a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
