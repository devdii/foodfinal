<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $code = $_POST['code'];
    
    // Connect to your database
    $conn = new mysqli("localhost", "root", "", "food");
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Check if verification code exists and is valid
    $stmt = $conn->prepare("SELECT * FROM verification_codes WHERE email = ? AND code = ? AND created_at > DATE_SUB(NOW(), INTERVAL 10 MINUTE)");
    $stmt->bind_param("ss", $email, $code);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Update verification status
        $stmt = $conn->prepare("UPDATE verification_codes SET is_verified = 1 WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        echo "success";
    } else {
        http_response_code(400);
        echo "Invalid or expired verification code";
    }
    
    $stmt->close();
    $conn->close();
}
?> 