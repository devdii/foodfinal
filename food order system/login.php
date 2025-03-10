<?php
require_once 'auth.php';

// If user is already logged in, redirect to home
if (isLoggedIn()) {
    header('Location: home1.php');
    exit();
}

$connect = mysqli_connect("localhost", "root", "", "food") or die("Connection failed: " . mysqli_connect_error());

// Handle session messages
$message = null;
$messageType = 'info';
if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
        case 'session_expired':
            $message = "Your session has expired. Please log in again.";
            $messageType = 'warning';
            break;
        case 'logged_out':
            $message = "You have been successfully logged out.";
            $messageType = 'success';
            break;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $error = null;

    try {
        if (empty($email) || empty($password)) {
            throw new Exception("Please fill in all fields");
        }

        $stmt = $connect->prepare("SELECT * FROM users WHERE Email = ?");
        if (!$stmt) {
            throw new Exception("Database error occurred");
        }

        $stmt->bind_param("s", $email);
        if (!$stmt->execute()) {
            throw new Exception("Failed to verify credentials");
        }

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['Pwd'])) {
            // Set session with user details
            setUserSession($email, $email, $user['Role'] ?? 'user');
            
            error_log("Login successful for email: $email");
            header('Location: home1.php');
            exit();
        } else {
            throw new Exception("Invalid email or password");
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        error_log("Login error for $email: " . $e->getMessage());
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FoodHub</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    <style>
        body {
            background-image: url("images/abt.jpg");
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .login_outer {
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
        .forgot-password {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #2980b9;
            text-decoration: none;
        }
        .forgot-password:hover {
            text-decoration: underline;
            color: #2471a3;
        }
        .back-link {
            display: inline-block;
            padding: 8px 15px;
            color: #2980b9;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s;
        }
        .back-link:hover {
            background-color: #2980b9;
            color: white;
            text-decoration: none;
        }
        .input-group-text {
            cursor: pointer;
        }
        .alert-warning {
            color: #856404;
            background-color: #fff3cd;
            border-color: #ffeeba;
        }
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .alert-info {
            color: #0c5460;
            background-color: #d1ecf1;
            border-color: #bee5eb;
        }
    </style>
</head>
<body>
    <div class="href" align="left" style="padding: 15px;">
        <a href="./index.php" class="back-link">
            <i class="fas fa-user-plus"></i> Create New Account
        </a>
    </div>

    <div class="container-fluid">
        <div class="row d-flex justify-content-center align-items-center m-0" style="height: 100vh;">
            <div class="login_outer">
                <?php if (isset($message)): ?>
                    <div class="alert alert-<?php echo $messageType; ?>" role="alert">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form action="" method="post" id="loginForm" autocomplete="off">
                    <h4 align="center">Login to FoodHub</h4>
                    <hr>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="email" class="form-control" id="email" name="email" 
                                   required placeholder="Enter your email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            </div>
                            <input type="password" class="form-control" id="password" name="password" 
                                   required placeholder="Enter your password">
                            <div class="input-group-append">
                                <span class="input-group-text" onclick="togglePassword()">
                                    <i class="fas fa-eye" id="togglePassword"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="save" id="btn" class="btn btn-primary">Login</button>
                    <a href="forgot_password.php" class="forgot-password">Forgot Password?</a>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('togglePassword');
            
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

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            
            if (!email || !password) {
                e.preventDefault();
                alert('Please fill in all fields');
                return false;
            }
            
            if (!email.includes('@')) {
                e.preventDefault();
                alert('Please enter a valid email address');
                return false;
            }
            
            return true;
        });
    </script>
</body>
</html>
