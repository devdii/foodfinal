<?php
require_once 'auth.php';

// Check authentication
checkAuth();

@include 'config.php';

// Get current session ID
$sessionid = session_id();

// Get cart count
$cart_count_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM `cart` WHERE sessionid = '$sessionid'");
$cart_count = mysqli_fetch_assoc($cart_count_query)['count'];

// Return JSON response
header('Content-Type: application/json');
echo json_encode(['count' => $cart_count]);
?>
