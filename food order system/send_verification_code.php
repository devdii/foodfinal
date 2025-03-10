<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $verificationCode = $_POST['code'];

    // Connect to database
    $conn = new mysqli("localhost", "root", "", "food");
    
    if ($conn->connect_error) {
        http_response_code(500);
        echo "Database connection failed: " . $conn->connect_error;
        exit();
    }

    // Store verification code in database
    $stmt = $conn->prepare("INSERT INTO verification_codes (email, code, created_at) VALUES (?, ?, NOW()) ON DUPLICATE KEY UPDATE code = ?, created_at = NOW()");
    $stmt->bind_param("sss", $email, $verificationCode, $verificationCode);
    
    if (!$stmt->execute()) {
        http_response_code(500);
        echo "Failed to store verification code: " . $stmt->error;
        exit();
    }

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'welcometoomyportfolio@gmail.com';
        $mail->Password = 'nvdz zfui saxw ylak'; // Keep your existing App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Changed to SMTPS
        $mail->Port = 465; // Changed to 465 for SMTPS
        
        // Set timeout and debug options
        $mail->Timeout = 60; // Increased timeout
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->SMTPKeepAlive = true;

        // Recipients
        $mail->setFrom('welcometoomyportfolio@gmail.com', 'Food Order System');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Email Verification Code';
        $mail->Body = "
            <html>
            <body style='font-family: Arial, sans-serif;'>
                <h2>Email Verification</h2>
                <p>Your verification code is: <b style='font-size: 20px;'>{$verificationCode}</b></p>
                <p>This code will expire in 10 minutes.</p>
                <p>If you didn't request this code, please ignore this email.</p>
            </body>
            </html>
        ";
        $mail->AltBody = "Your verification code is: {$verificationCode}";

        // Attempt to send email
        if (!$mail->send()) {
            throw new Exception("Mailer Error: " . $mail->ErrorInfo);
        }

        // Log successful email sending
        error_log("Verification email sent successfully to: " . $email);
        echo "Verification code sent successfully";
        
    } catch (Exception $e) {
        error_log("Failed to send verification email: " . $e->getMessage());
        http_response_code(500);
        echo "Failed to send verification email: " . $e->getMessage();
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(405);
    echo "Invalid request method";
}
?> 