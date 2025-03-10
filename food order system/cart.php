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

if(isset($_POST['update_update_btn'])){
   $update_value = (int)$_POST['update_quantity'];
   $update_id = (int)$_POST['update_quantity_id'];
   $update_quantity_query = mysqli_query($conn, "UPDATE `cart` SET quantity = '$update_value' WHERE id = '$update_id' AND sessionid = '$sessionid'");
   if($update_quantity_query){
      header('location:cart.php');
   };
};

if(isset($_GET['remove'])){
   $remove_id = (int)$_GET['remove'];
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$remove_id' AND sessionid = '$sessionid'");
   header('location:cart.php');
};

if(isset($_GET['delete_all'])){
   mysqli_query($conn, "DELETE FROM `cart` WHERE sessionid = '$sessionid'");
   header('location:cart.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Cart - FoodHub</title>
   <link rel="shortcut icon" type="image/png" href="images/icon.png">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
   <style>
      body {
         font-family: 'Poppins', sans-serif;
         background-color: #f8f9fa;
         padding-top: 80px;
      }
      .cart-container {
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
      .table {
         margin-bottom: 30px;
      }
      .table th {
         border-top: none;
         background: #f8f9fa;
         font-weight: 600;
         color: #2d3436;
      }
      .table td {
         vertical-align: middle;
      }
      .product-img {
         width: 80px;
         height: 80px;
         object-fit: cover;
         border-radius: 10px;
      }
      .quantity-input {
         width: 70px;
         text-align: center;
         margin-right: 10px;
         border: 1px solid #dee2e6;
         border-radius: 5px;
         padding: 5px;
      }
      .update-btn {
         background: #2980b9;
         color: white;
         border: none;
         padding: 5px 15px;
         border-radius: 5px;
         transition: all 0.3s;
      }
      .update-btn:hover {
         background: #2471a3;
         transform: translateY(-2px);
      }
      .delete-btn {
         color: #e74c3c;
         text-decoration: none;
         transition: all 0.3s;
      }
      .delete-btn:hover {
         color: #c0392b;
         text-decoration: none;
      }
      .continue-shopping {
         background: #2980b9;
         color: white;
         padding: 10px 20px;
         border-radius: 25px;
         text-decoration: none;
         transition: all 0.3s;
      }
      .continue-shopping:hover {
         background: #2471a3;
         color: white;
         transform: translateY(-2px);
         text-decoration: none;
      }
      .checkout-btn {
         background: linear-gradient(45deg, #FF416C, #FF4B2B);
         color: white;
         padding: 12px 30px;
         border-radius: 25px;
         text-decoration: none;
         transition: all 0.3s;
         border: none;
      }
      .checkout-btn:hover {
         background: linear-gradient(45deg, #FF4B2B, #FF416C);
         color: white;
         transform: translateY(-2px);
         text-decoration: none;
         box-shadow: 0 5px 15px rgba(255, 65, 108, 0.3);
      }
      .checkout-btn.disabled {
         background: #6c757d;
         cursor: not-allowed;
      }
      .total-amount {
         font-size: 1.2em;
         font-weight: 600;
         color: #2d3436;
      }
      .empty-cart {
         text-align: center;
         padding: 50px 0;
      }
      .empty-cart i {
         font-size: 5em;
         color: #6c757d;
         margin-bottom: 20px;
      }
      .empty-cart p {
         color: #6c757d;
         font-size: 1.2em;
         margin-bottom: 30px;
      }
   </style>
</head>
<body>
   <?php include 'navbar.php'; ?>

   <div class="container">
      <div class="cart-container">
         <h1 class="heading">Shopping Cart</h1>

         <?php 
         $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE sessionid = '$sessionid'");
         $grand_total = 0;
         
         if(mysqli_num_rows($select_cart) > 0){
         ?>

         <div class="table-responsive">
            <table class="table">
               <thead>
                  <tr>
                     <th>Product</th>
                     <th>Name</th>
                     <th>Price</th>
                     <th>Quantity</th>
                     <th>Total</th>
                     <th>Action</th>
                  </tr>
               </thead>
               <tbody>
                  <?php
                  while($fetch_cart = mysqli_fetch_assoc($select_cart)){
                     $sub_total = $fetch_cart['price'] * $fetch_cart['quantity'];
                     $grand_total += $sub_total;
                  ?>
                  <tr>
                     <td>
                        <img src="admin/images/<?php echo htmlspecialchars($fetch_cart['image']); ?>" 
                             class="product-img" 
                             alt="<?php echo htmlspecialchars($fetch_cart['name']); ?>">
                     </td>
                     <td><?php echo htmlspecialchars($fetch_cart['name']); ?></td>
                     <td>₹<?php echo number_format($fetch_cart['price']); ?>/-</td>
                     <td>
                        <form action="" method="post" class="d-flex align-items-center">
                           <input type="hidden" name="update_quantity_id" value="<?php echo $fetch_cart['id']; ?>">
                           <input type="number" name="update_quantity" min="1" value="<?php echo $fetch_cart['quantity']; ?>" 
                                  class="quantity-input">
                           <button type="submit" name="update_update_btn" class="update-btn">
                              <i class="fas fa-sync-alt"></i>
                           </button>
                        </form>   
                     </td>
                     <td>₹<?php echo number_format($sub_total); ?>/-</td>
                     <td>
                        <a href="cart.php?remove=<?php echo $fetch_cart['id']; ?>" 
                           onclick="return confirm('Remove item from cart?')" 
                           class="delete-btn">
                           <i class="fas fa-trash"></i>
                        </a>
                     </td>
                  </tr>
                  <?php
                  }
                  ?>
                  <tr>
                     <td colspan="4" class="text-right total-amount">Total Amount:</td>
                     <td colspan="2">₹<?php echo number_format($grand_total); ?>/-</td>
                  </tr>
               </tbody>
            </table>
         </div>

         <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="products.php" class="continue-shopping">
               <i class="fas fa-arrow-left mr-2"></i>Continue Shopping
            </a>
            <div>
               <a href="cart.php?delete_all" 
                  onclick="return confirm('Are you sure you want to delete all items?');" 
                  class="btn btn-danger mr-2">
                  <i class="fas fa-trash mr-1"></i>Clear Cart
               </a>
               <a href="checkout.php" class="checkout-btn <?php echo ($grand_total > 0) ? '' : 'disabled' ?>">
                  <i class="fas fa-shopping-cart mr-1"></i>Proceed to Checkout
               </a>
            </div>
         </div>

         <?php
         } else {
            echo '<div class="empty-cart">
                     <i class="fas fa-shopping-cart"></i>
                     <p>Your cart is empty</p>
                     <a href="products.php" class="continue-shopping">
                        <i class="fas fa-arrow-left mr-2"></i>Continue Shopping
                     </a>
                  </div>';
         }
         ?>
      </div>
   </div>

   <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>