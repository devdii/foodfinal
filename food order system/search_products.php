<?php
require_once 'auth.php';
@include 'config.php';

// Add error logging function
function logSearchError($message) {
    $logFile = 'search_errors.log';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message\n";
    error_log($logMessage, 3, $logFile);
}

header('Content-Type: application/json');

try {
    if (!isset($conn)) {
        throw new Exception("Database connection failed");
    }

    if (isset($_GET['query'])) {
        $search = mysqli_real_escape_string($conn, $_GET['query']);
        
        // Search only in name since category doesn't exist
        $query = "SELECT * FROM `product` WHERE 
                 `name` LIKE '%$search%'
                 ORDER BY `name` ASC";
        
        logSearchError("Executing query: " . $query);
                 
        $result = mysqli_query($conn, $query);
        
        if (!$result) {
            throw new Exception("Query failed: " . mysqli_error($conn));
        }
        
        $products = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = array(
                'id' => $row['id'],
                'name' => $row['name'],
                'price' => $row['price'],
                'image' => $row['image']
            );
        }
        
        logSearchError("Found " . count($products) . " products for query: " . $search);
        echo json_encode(['success' => true, 'products' => $products]);
    } else {
        throw new Exception("No search query provided");
    }
} catch (Exception $e) {
    logSearchError("Search error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
