<?php
require_once 'auth.php';
@include 'config.php';

header('Content-Type: application/json');

try {
    if (!isset($_POST['order_id']) || !isset($_POST['status'])) {
        throw new Exception('Order ID and status are required');
    }

    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Validate status
    $valid_statuses = ['preparing', 'ready', 'out_for_delivery', 'delivered'];
    if (!in_array($status, $valid_statuses)) {
        throw new Exception('Invalid status');
    }

    $update_query = "UPDATE `corder` SET `status` = '$status' WHERE `id` = '$order_id'";
    if (!mysqli_query($conn, $update_query)) {
        throw new Exception(mysqli_error($conn));
    }

    echo json_encode(['success' => true, 'message' => 'Order status updated successfully']);
} catch (Exception $e) {
    error_log("Order status update error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
