<?php
require_once 'auth.php';

// Check authentication and session timeout
checkAuth();
if (checkSessionTimeout()) {
    header('Location: login.php?msg=session_expired');
    exit();
}

@include 'config.php';

// Get current session ID
$sessionid = session_id();

// Add error logging function
function logError($message) {
    $logFile = 'payment_errors.log';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message\n";
    error_log($logMessage, 3, $logFile);
}

if(isset($_POST['order_btn'])){
    try {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $number = mysqli_real_escape_string($conn, $_POST['number']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $method = mysqli_real_escape_string($conn, $_POST['method']);
        $flat = mysqli_real_escape_string($conn, $_POST['flat']);
        $street = mysqli_real_escape_string($conn, $_POST['street']);
        $city = mysqli_real_escape_string($conn, $_POST['city']);
        $pin_code = mysqli_real_escape_string($conn, $_POST['pin_code']);

        $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE sessionid = '$sessionid'");
        $price_total = 0;
        $product_details = array();
        
        if(mysqli_num_rows($cart_query) > 0){
            while($product_item = mysqli_fetch_assoc($cart_query)){
                $item_total = $product_item['price'] * $product_item['quantity'];
                $product_details[] = array(
                    'name' => $product_item['name'],
                    'quantity' => $product_item['quantity'],
                    'price' => $product_item['price'],
                    'total' => $item_total
                );
                $price_total += $item_total;
            };
        };

        if(empty($product_details)) {
            header('Location: cart.php');
            exit();
        }

        // Convert product details to JSON for storage
        $total_products = json_encode($product_details);

        // Insert order into database
        $detail_query = mysqli_query($conn, "INSERT INTO `corder`(name, number, email, method, flat, street, city, pin_code, total_products, total_price, status, sessionid, order_time) VALUES('$name','$number','$email','$method','$flat','$street','$city','$pin_code','$total_products','$price_total', 'preparing', '$sessionid', NOW())") or die('query failed');

        if($detail_query) {
            $order_id = mysqli_insert_id($conn); // Get the last inserted order ID
            mysqli_query($conn, "DELETE FROM `cart` WHERE sessionid = '$sessionid'") or die('query failed');
            $_SESSION['last_order_id'] = $order_id; // Store order ID in session
            header('Location: order_status.php?order_id='.$order_id.'&success=1');
            exit();
        }

    } catch (Exception $e) {
        logError($e->getMessage());
        header('Location: checkout.php?error=1');
        exit();
    }
}

// Get user details if logged in
if (isset($_SESSION['user_id'])) {
    $user_id = mysqli_real_escape_string($conn, $_SESSION['user_id']);
    $user_query = mysqli_query($conn, "SELECT * FROM `users` WHERE `id` = '$user_id'");
    if ($user_query) {
        $user_data = mysqli_fetch_assoc($user_query);
    } else {
        logError("Failed to fetch user data: " . mysqli_error($conn));
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout - FoodHub</title>
   <link rel="shortcut icon" type="image/png" href="images/icon.png">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
   <style>
      body {
         font-family: 'Poppins', sans-serif;
         background-color: #f8f9fa;
         padding-top: 80px;
      }
      .checkout-container {
         background: #fff;
         border-radius: 15px;
         box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
         padding: 30px;
         margin-bottom: 30px;
      }
      .heading {
         text-align: center;
         margin-bottom: 30px;
         color: #2d3436;
         font-weight: 600;
      }
      .display-order {
         background: #f8f9fa;
         padding: 20px;
         border-radius: 10px;
         margin-bottom: 30px;
      }
      .display-order span {
         display: block;
         margin: 5px 0;
         font-size: 1.1em;
      }
      .grand-total {
         font-size: 1.3em;
         color: #e67e22;
         font-weight: 600;
         margin-top: 15px;
         display: block;
         text-align: right;
      }
      .form-group label {
         font-weight: 500;
         color: #2d3436;
      }
      .form-control {
         border-radius: 8px;
         border: 1px solid #dee2e6;
         padding: 10px 15px;
      }
      .form-control:focus {
         border-color: #e67e22;
         box-shadow: 0 0 0 0.2rem rgba(230, 126, 34, 0.25);
      }
      select.form-control {
         height: 45px;
      }
      .btn-order {
         background: linear-gradient(45deg, #FF416C, #FF4B2B);
         color: white;
         padding: 12px 30px;
         border-radius: 25px;
         font-weight: 500;
         letter-spacing: 1px;
         width: 100%;
         margin-top: 20px;
         border: none;
         transition: all 0.3s;
      }
      .btn-order:hover {
         background: linear-gradient(45deg, #FF4B2B, #FF416C);
         transform: translateY(-2px);
         box-shadow: 0 5px 15px rgba(255, 65, 108, 0.3);
      }
      .error-message {
         background-color: #ff6b6b;
         color: white;
         padding: 15px;
         border-radius: 8px;
         margin-bottom: 20px;
         text-align: center;
      }
      .payment-note {
         color: #6c757d;
         font-size: 0.9em;
         margin-top: 10px;
         text-align: center;
      }
   </style>
</head>
<body>
   <?php include 'navbar.php'; ?>

   <div class="container">
      <div class="checkout-container">
         <h1 class="heading">Complete Your Order</h1>

         <?php if(isset($_GET['error'])): ?>
         <div class="error-message">
            <i class="fas fa-exclamation-circle mr-2"></i>
            An error occurred while processing your order. Please try again.
         </div>
         <?php endif; ?>

         <form action="" method="post" id="checkoutForm">
            <div class="display-order">
               <?php
                  $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE sessionid = '$sessionid'");
                  $total = 0;
                  $grand_total = 0;
                  
                  if(mysqli_num_rows($select_cart) > 0){
                     while($fetch_cart = mysqli_fetch_assoc($select_cart)){
                        $total_price = $fetch_cart['price'] * $fetch_cart['quantity'];
                        $grand_total = $total += $total_price;
               ?>
               <span><?= htmlspecialchars($fetch_cart['name']); ?> (<?= htmlspecialchars($fetch_cart['quantity']); ?>)</span>
               <?php
                  }
               } else {
                  echo "<span>Your cart is empty!</span>";
                  echo "<script>window.location = 'cart.php';</script>";
               }
               ?>
               <span class="grand-total">Total Amount : ₹<?= number_format($grand_total); ?>/-</span>
            </div>

            <div class="row">
               <div class="col-md-6">
                  <div class="form-group">
                     <label>Full Name</label>
                     <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($user_data['Name'] ?? ''); ?>" required>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label>Phone Number</label>
                     <input type="tel" class="form-control" name="number" pattern="[789][0-9]{9}" value="<?= htmlspecialchars($user_data['Phone'] ?? ''); ?>" required>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label>Email Address</label>
                     <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user_data['email'] ?? ''); ?>" required>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label>Payment Method</label>
                     <select name="method" class="form-control">
                        <option value="cash on delivery">Cash on Delivery</option>
                        <option value="credit card">Credit Card</option>
                        <option value="UPI Apps">UPI (GPay, PhonePe, etc)</option>
                     </select>
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <label>Address Line 1</label>
                     <input type="text" class="form-control" name="flat" placeholder="Flat/House No., Building Name" required>
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <label>Address Line 2</label>
                     <input type="text" class="form-control" name="street" placeholder="Area, Landmark" required>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label>City</label>
                     <input type="text" class="form-control" name="city" required>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label>PIN Code</label>
                     <input type="text" class="form-control" name="pin_code" pattern="[0-9]{6}" maxlength="6" minlength="6" required>
                  </div>
               </div>
            </div>

            <button type="submit" name="order_btn" class="btn-order">
               <i class="fas fa-shopping-bag mr-2"></i>Place Order
            </button>
            
            <p class="payment-note mt-3">
               <i class="fas fa-info-circle mr-1"></i>
               For Cash on Delivery, payment will be collected upon delivery
            </p>
         </form>
      </div>
   </div>

   <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
   <script>
   // Prevent form resubmission
   if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
   }

   // Form validation
   document.getElementById('checkoutForm').addEventListener('submit', function(e) {
      const phoneInput = document.querySelector('input[name="number"]');
      const pinInput = document.querySelector('input[name="pin_code"]');
      
      if (!phoneInput.value.match(/^[789][0-9]{9}$/)) {
         e.preventDefault();
         alert('Please enter a valid 10-digit phone number starting with 7, 8, or 9');
         return;
      }
      
      if (!pinInput.value.match(/^[0-9]{6}$/)) {
         e.preventDefault();
         alert('Please enter a valid 6-digit PIN code');
         return;
      }
   });
   </script>
</body>
</html>