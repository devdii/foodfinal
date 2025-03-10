<?php
session_start();

if (!empty($_POST['save'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === 'admin' && $password === 'admin') {
        $_SESSION['admin'] = true; // Set admin session
        echo '<script type="text/javascript">';
        echo ' alert("Login Successfully");'; 
        echo '</script>';
        header('Location: index.php');
        exit();
    } else {
        echo '<script type="text/javascript">';
        echo ' alert("Invalid Username or Password");';  
        echo '</script>';
    }
}

// If already logged in, redirect to index
if(isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=\, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        .login_outer {
            width: 360px;
            max-width: 100%;
        }
        #btn {
            background-color: rgb(41,128,185);
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            font-size: 16px;
            cursor: pointer;
        }
        a:link, a:visited {
            background-color: white;
            color: black;
            border: 2px solid skyblue;
            padding: 10px 20px;
            text-decoration: none;
        }
        a:hover, a:active {
            background-color: rgb(41,128,185);
            color: white;
        }
    </style>
    <script>
        function password_show_hide() {
            var x = document.getElementById("password");
            var show_eye = document.getElementById("show_eye");
            var hide_eye = document.getElementById("hide_eye");
            hide_eye.classList.remove("d-none");
            if (x.type === "password") {
                x.type = "text";
                show_eye.style.display = "none";
                hide_eye.style.display = "block";
            } else {
                x.type = "password";
                show_eye.style.display = "block";
                hide_eye.style.display = "none";
            }
        }
    </script>
</head>
<body>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" />

    <div class="href" align="left">
        <a href="http://localhost/food order system/food order system/home1.php">Back</a>
    </div>

    <div class="container-fluid">
        <div class="row d-flex justify-content-center align-items-center m-0" style="height: 100vh;">
            <div class="login_outer">
                <form action="" method="post" class="bg-light border p-3">
                    <h4 align="center">Admin Login</h4>
                    <hr>
                    <div class="form-row" align="center">
                        <div class="col-12">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input name="username" type="text" class="form-control" placeholder="Username" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                </div>
                                <input name="password" type="password" class="form-control" id="password" placeholder="Password" required>
                                <div class="input-group-append">
                                    <span class="input-group-text" onclick="password_show_hide();">
                                        <i class="fas fa-eye" id="show_eye"></i>
                                        <i class="fas fa-eye-slash d-none" id="hide_eye"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <input type="submit" name="save" id="btn" value="Login"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
