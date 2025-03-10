<?php
class AuthHelper {
    private $db;
    private const TOKEN_EXPIRY_HOURS = 1;
    private const SESSION_EXPIRY_HOURS = 24;

    public function __construct($db) {
        $this->db = $db;
        $this->initSession();
    }

    private function initSession() {
        if (session_status() === PHP_SESSION_NONE) {
            // Set secure session parameters
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_only_cookies', 1);
            ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
            
            session_start();
        }

        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > self::SESSION_EXPIRY_HOURS * 3600)) {
            // Session expired
            $this->logout();
        }
        $_SESSION['LAST_ACTIVITY'] = time();
    }

    public function isValidResetToken($token) {
        if (empty($token)) {
            error_log("Empty reset token provided");
            return ['valid' => false, 'expired' => false];
        }

        try {
            $stmt = $this->db->prepare("SELECT Email, reset_token, reset_token_expiry FROM users WHERE reset_token = ?");
            if (!$stmt) {
                error_log("Failed to prepare token validation query: " . $this->db->error);
                return ['valid' => false, 'expired' => false];
            }

            $stmt->bind_param("s", $token);
            if (!$stmt->execute()) {
                error_log("Failed to execute token validation query: " . $stmt->error);
                return ['valid' => false, 'expired' => false];
            }

            $result = $stmt->get_result();
            if ($result->num_rows === 0) {
                error_log("No user found with token: " . $token);
                return ['valid' => false, 'expired' => false];
            }

            $user = $result->fetch_assoc();
            $currentTime = new DateTime();
            $expiryTime = new DateTime($user['reset_token_expiry']);

            error_log("Token validation - Current time: " . $currentTime->format('Y-m-d H:i:s') . 
                     ", Token expiry: " . $expiryTime->format('Y-m-d H:i:s'));

            if ($currentTime > $expiryTime) {
                error_log("Token expired for user: " . $user['Email']);
                return ['valid' => false, 'expired' => true, 'email' => $user['Email']];
            }

            error_log("Valid token for user: " . $user['Email']);
            return ['valid' => true, 'expired' => false, 'email' => $user['Email']];
        } catch (Exception $e) {
            error_log("Error validating reset token: " . $e->getMessage());
            return ['valid' => false, 'expired' => false];
        }
    }

    public function createResetToken($email) {
        try {
            $token = bin2hex(random_bytes(32));
            $expiry = (new DateTime())->modify('+' . self::TOKEN_EXPIRY_HOURS . ' hour')
                                    ->format('Y-m-d H:i:s');

            $stmt = $this->db->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE Email = ?");
            if (!$stmt) {
                throw new Exception("Failed to prepare token creation query: " . $this->db->error);
            }

            $stmt->bind_param("sss", $token, $expiry, $email);
            if (!$stmt->execute()) {
                throw new Exception("Failed to create reset token: " . $stmt->error);
            }

            if ($stmt->affected_rows === 0) {
                throw new Exception("No user found with email: " . $email);
            }

            error_log("Reset token created for {$email}, expires: {$expiry}");
            return $token;
        } catch (Exception $e) {
            error_log("Error creating reset token: " . $e->getMessage());
            throw $e;
        }
    }

    public function resetPassword($token, $newPassword) {
        try {
            $tokenStatus = $this->isValidResetToken($token);
            if (!$tokenStatus['valid']) {
                throw new Exception($tokenStatus['expired'] ? "Token has expired" : "Invalid token");
            }

            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            $stmt = $this->db->prepare("UPDATE users SET Pwd = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?");
            if (!$stmt) {
                throw new Exception("Failed to prepare password reset query: " . $this->db->error);
            }

            $stmt->bind_param("ss", $hashedPassword, $token);
            if (!$stmt->execute()) {
                throw new Exception("Failed to reset password: " . $stmt->error);
            }

            error_log("Password successfully reset for user with token: " . $token);
            return true;
        } catch (Exception $e) {
            error_log("Error resetting password: " . $e->getMessage());
            throw $e;
        }
    }

    public function logout() {
        $_SESSION = array();
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        session_destroy();
    }
}
