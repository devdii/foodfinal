<?php
header('Content-Type: application/json');
require_once 'auth.php';
@include 'config.php';

// Add error logging
function logError($message) {
    error_log(date('Y-m-d H:i:s') . " - " . $message . "\n", 3, "cart_errors.log");
}

// Get current session ID
$sessionid = session_id();

try {
    if(isset($_POST['product_name'])) {
        $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
        $product_price = mysqli_real_escape_string($conn, $_POST['product_price']);
        $product_image = mysqli_real_escape_string($conn, $_POST['product_image']);
        $product_quantity = 1;

        // Log the incoming data
        logError("Adding to cart - Name: $product_name, Price: $product_price, Image: $product_image");

        $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND sessionid = '$sessionid'");
        
        if(!$select_cart) {
            logError("Select query failed: " . mysqli_error($conn));
            echo json_encode(['success' => false, 'message' => 'Database error']);
            exit;
        }

        if(mysqli_num_rows($select_cart) > 0){
            logError("Product already in cart: $product_name");
            echo json_encode(['success' => false, 'message' => 'Product already added to cart']);
            exit;
        }

        $insert_product = mysqli_query($conn, "INSERT INTO `cart`(name, price, image, quantity, sessionid) VALUES('$product_name', '$product_price', '$product_image', '$product_quantity', '$sessionid')");
        
        if(!$insert_product) {
            logError("Insert failed: " . mysqli_error($conn));
            echo json_encode(['success' => false, 'message' => 'Failed to add product']);
            exit;
        }

        // Get updated cart count
        $count_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM `cart` WHERE sessionid = '$sessionid'");
        $new_count = mysqli_fetch_assoc($count_query)['count'];
        
        logError("Product added successfully. New cart count: $new_count");
        echo json_encode(['success' => true, 'message' => 'Product added to cart successfully', 'count' => $new_count]);
        exit;
    } else {
        logError("No product data received");
        echo json_encode(['success' => false, 'message' => 'No product data received']);
        exit;
    }
} catch (Exception $e) {
    logError("Exception: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred']);
    exit;
}
?>
