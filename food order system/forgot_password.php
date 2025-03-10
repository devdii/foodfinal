<?php
require_once 'auth.php';
require_once 'EmailHelper.php';
require_once 'AuthHelper.php';

$connect = mysqli_connect("localhost", "root", "", "food") or die("Connection failed: " . mysqli_connect_error());
$auth = new AuthHelper($connect);
$emailSent = false;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    
    if (!empty($email)) {
        try {
            // Check if email exists
            $stmt = $connect->prepare("SELECT * FROM users WHERE Email = ?");
            if (!$stmt) {
                throw new Exception("Database error occurred");
            }

            $stmt->bind_param("s", $email);
            if (!$stmt->execute()) {
                throw new Exception("Failed to check email");
            }

            $result = $stmt->get_result();
            if ($result->num_rows === 0) {
                throw new Exception("No account found with this email address.");
            }

            // Create reset token
            $token = $auth->createResetToken($email);
            
            // Create reset link
            $resetLink = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/reset_password.php?token=" . $token;
            
            // Send email
            $emailHelper = new EmailHelper();
            $emailHelper->sendPasswordResetEmail($email, $resetLink);
            $emailSent = true;
            
        } catch (Exception $e) {
            $error = $e->getMessage();
            error_log("Password reset request error: " . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }
    } else {
        $error = "Please enter your email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    <style>
        body {
            background-image: url("images/abt.jpg");
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .forgot_outer {
            width: 360px;
            max-width: 100%;
            margin: auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        #btn {
            background-color: rgb(41, 128, 185);
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
            width: 100%;
        }
        #btn:hover {
            background-color: rgb(31, 97, 145);
        }
        .alert {
            margin-bottom: 20px;
            word-break: break-word;
        }
    </style>
</head>
<body>
    <div class="href" align="left">
        <a href="./login.php" class="btn btn-link">Back to Login</a>
    </div>

    <div class="container-fluid">
        <div class="row d-flex justify-content-center align-items-center m-0" style="height: 100vh;">
            <div class="forgot_outer">
                <?php if ($emailSent): ?>
                    <div class="alert alert-success" role="alert">
                        <h5>Reset Link Sent!</h5>
                        <p>If an account exists with the email address you provided, you will receive password reset instructions shortly.</p>
                        <p>Please check your email and follow the instructions to reset your password.</p>
                        <p>The reset link will expire in 1 hour for security reasons.</p>
                    </div>
                    <div class="text-center">
                        <a href="login.php" class="btn btn-primary">Return to Login</a>
                    </div>
                <?php else: ?>
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="post" autocomplete="off">
                        <h4 align="center">Forgot Password</h4>
                        <hr>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="email" class="form-control" id="email" name="email" 
                                       required placeholder="Enter your email address">
                            </div>
                            <small class="form-text text-muted">
                                Enter the email address associated with your account
                            </small>
                        </div>
                        <button type="submit" id="btn">Send Reset Link</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
