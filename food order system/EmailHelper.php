<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/autoload.php';
require_once 'email_config.php';

class EmailHelper {
    private $mailer;

    public function __construct() {
        $this->mailer = new PHPMailer(true);
        $this->setupMailer();
    }

    private function setupMailer() {
        try {
            // Server settings
            $this->mailer->isSMTP();
            $this->mailer->Host = SMTP_HOST;
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = SMTP_USERNAME;
            $this->mailer->Password = SMTP_PASSWORD;
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $this->mailer->Port = SMTP_PORT;

            // Set timeout and debug options
            $this->mailer->Timeout = 60;
            $this->mailer->SMTPDebug = 0; // Disable debug output
            $this->mailer->SMTPKeepAlive = true;

            // Default sender
            $this->mailer->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        } catch (Exception $e) {
            error_log("Error setting up mailer: " . $e->getMessage());
            throw new Exception("Email system configuration error");
        }
    }

    public function sendPasswordResetEmail($userEmail, $resetLink) {
        try {
            // Clear previous recipients
            $this->mailer->clearAddresses();
            
            // Recipients
            $this->mailer->addAddress($userEmail);

            // Content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Password Reset Request - Food Order System';
            
            // Email template
            $emailBody = $this->getPasswordResetEmailTemplate($resetLink);
            $this->mailer->Body = $emailBody;
            $this->mailer->AltBody = strip_tags(str_replace(['<br>', '</p>'], "\n", $emailBody));

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            error_log("Error sending password reset email: " . $e->getMessage());
            throw new Exception("Failed to send password reset email");
        }
    }

    private function getPasswordResetEmailTemplate($resetLink) {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #2980b9; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background-color: #f9f9f9; }
                .button { display: inline-block; padding: 10px 20px; background-color: #2980b9; 
                         color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
                .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>Password Reset Request</h2>
                </div>
                <div class="content">
                    <p>Hello,</p>
                    <p>We received a request to reset your password for your Food Order System account. 
                    If you did not make this request, please ignore this email.</p>
                    <p>To reset your password, click the button below:</p>
                    <p style="text-align: center;">
                        <a href="' . htmlspecialchars($resetLink) . '" class="button" style="color: white;">Reset Password</a>
                    </p>
                    <p>Or copy and paste this link into your browser:</p>
                    <p>' . htmlspecialchars($resetLink) . '</p>
                    <p>This link will expire in 1 hour for security reasons.</p>
                    <p>If you have any issues, please contact our support team.</p>
                </div>
                <div class="footer">
                    <p>This is an automated message, please do not reply to this email.</p>
                    <p>&copy; ' . date('Y') . ' Food Order System. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>';
    }
}
