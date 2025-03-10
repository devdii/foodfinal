<?php
require_once 'auth.php';
require_once 'AuthHelper.php';

$connect = mysqli_connect("localhost", "root", "", "food") or die("Connection failed: " . mysqli_connect_error());

$auth = new AuthHelper($connect);
$token = $_GET['token'] ?? '';
$error = null;
$tokenStatus = ['valid' => false, 'expired' => false];

try {
    if (!empty($token)) {
        $tokenStatus = $auth->isValidResetToken($token);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $tokenStatus['valid']) {
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        if (empty($password) || empty($confirm_password)) {
            throw new Exception("Please fill in all password fields.");
        }
        
        if ($password !== $confirm_password) {
            throw new Exception("Passwords do not match.");
        }
        
        if (strlen($password) < 6) {
            throw new Exception("Password must be at least 6 characters long.");
        }
        
        if ($auth->resetPassword($token, $password)) {
            echo "<script>
                alert('Password has been reset successfully!');
                window.location.href = 'login.php';
            </script>";
            exit();
        }
    }
} catch (Exception $e) {
    $error = $e->getMessage();
    error_log("Password reset error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" />
    <style>
        body {
            background-image: url("images/abt.jpg");
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .reset_outer {
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
        }
        #btn:hover {
            background-color: rgb(31, 97, 145);
        }
        .alert {
            margin-bottom: 20px;
            word-break: break-word;
        }
        .input-group-text {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="href" align="left">
        <a href="./login.php" class="btn btn-link">Back to Login</a>
    </div>

    <div class="container-fluid">
        <div class="row d-flex justify-content-center align-items-center m-0" style="height: 100vh;">
            <div class="reset_outer">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <?php if (!$tokenStatus['valid']): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php if ($tokenStatus['expired']): ?>
                            <h5>Password Reset Link Expired</h5>
                            <p>This password reset link has expired for security reasons. Please request a new one.</p>
                        <?php else: ?>
                            <h5>Invalid Reset Link</h5>
                            <p>This password reset link is invalid or has already been used. Please request a new password reset.</p>
                        <?php endif; ?>
                    </div>
                    <div class="text-center">
                        <a href="forgot_password.php" class="btn btn-primary">Request New Reset Link</a>
                    </div>
                <?php else: ?>
                    <form action="" method="post" id="resetForm" autocomplete="off">
                        <h4 align="center">Reset Password</h4>
                        <hr>
                        <div class="form-row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="password">New Password</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        </div>
                                        <input type="password" class="form-control" id="password" name="password" 
                                               required minlength="6" placeholder="Enter new password">
                                        <div class="input-group-append">
                                            <span class="input-group-text" onclick="togglePassword('password')">
                                                <i class="fas fa-eye" id="togglePassword"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Password must be at least 6 characters long</small>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="confirm_password">Confirm Password</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        </div>
                                        <input type="password" class="form-control" id="confirm_password" 
                                               name="confirm_password" required placeholder="Confirm new password">
                                        <div class="input-group-append">
                                            <span class="input-group-text" onclick="togglePassword('confirm_password')">
                                                <i class="fas fa-eye" id="toggleConfirmPassword"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" id="btn" class="btn btn-primary btn-block">
                                    Reset Password
                                </button>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.parentElement.querySelector('.fa-eye, .fa-eye-slash');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        document.getElementById('resetForm')?.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters long!');
                return false;
            }
            
            return true;
        });
    </script>
</body>
</html>
