<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="shortcut icon" type="image/png" href="images/icon.png">
    <link href="https://fonts.googleapis.com/css2?family=Assistant:wght@300&family=IBM+Plex+Sans&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            overflow: hidden;
        }

        .video-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            object-fit: cover;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            width: 100%;
            max-width: 400px;
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }

        .form-container h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #333;
        }

        .form-container input {
            width: 100%;
            padding: 0.75rem;
            margin: 0.5rem 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-container input:focus {
            border-color: #667eea;
            outline: none;
        }

        .form-container button {
            width: 100%;
            padding: 0.75rem;
            margin: 1rem 0;
            background-color: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #764ba2;
        }

        .form-container a {
            display: block;
            margin-top: 1rem;
            color: #667eea;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .form-container a:hover {
            color: #764ba2;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 1rem;
            }

            .form-container h3 {
                font-size: 1.25rem;
            }

            .form-container input {
                padding: 0.5rem;
            }

            .form-container button {
                padding: 0.5rem;
            }
        }
    </style>
    <script>
        function validateform() {
            if (!sessionStorage.getItem("isVerified")) {
                alert("Please verify your email first.");
                return false;
            }
            
            var x = document.forms["myForm"]["Name"].value;
            var y = document.forms["myForm"]["Phone"].value;
            var a = document.forms["myForm"]["Email"].value;
            var c = document.forms["myForm"]["Pwd"].value;

            if (x == "" || y == "" || a == "" || c == "") {
                alert("Please fill all the fields.");
                return false;
            }

            var phonePattern = /^\d{10}$/;
            if (!y.match(phonePattern)) {
                alert("Please enter a valid 10-digit phone number.");
                return false;
            }

            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!a.match(emailPattern)) {
                alert("Please enter a valid email address.");
                return false;
            }

            return true;
        }

        function sendVerificationCode() {
            var email = document.forms["myForm"]["Email"].value;
            
            if (!email) {
                console.error("Email field is empty.");
                alert("Please enter an email address first.");
                return false;
            }

            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email.match(emailPattern)) {
                console.error("Invalid email format.");
                alert("Please enter a valid email address.");
                return false;
            }

            var verificationCode = Math.floor(100000 + Math.random() * 900000);
            
            // Show loading state
            document.getElementById("sendVerificationBtn").disabled = true;
            document.getElementById("sendVerificationBtn").textContent = "Sending...";

            // Send verification code via AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "send_verification_code.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    // Reset button state
                    document.getElementById("sendVerificationBtn").disabled = false;
                    document.getElementById("sendVerificationBtn").textContent = "Send Verification Code";
                    
                    if (xhr.status === 200) {
                        console.log("Verification code sent successfully.");
                        alert("A verification code has been sent to " + email + ". Please check your email to verify.");
                        document.getElementById("verificationCodeInput").style.display = "block";
                        sessionStorage.setItem("verificationCode", verificationCode);
                    } else {
                        console.error("Failed to send verification code. Status: " + xhr.status);
                        console.error("Response: " + xhr.responseText);
                        alert("Failed to send verification code. Please try again later.\nError: " + xhr.responseText);
                    }
                }
            };
            
            // Add timeout handling
            xhr.timeout = 30000; // 30 seconds timeout
            xhr.ontimeout = function () {
                console.error("Request timed out");
                document.getElementById("sendVerificationBtn").disabled = false;
                document.getElementById("sendVerificationBtn").textContent = "Send Verification Code";
                alert("Request timed out. Please try again.");
            };

            xhr.send("email=" + encodeURIComponent(email) + "&code=" + verificationCode);
            return false;
        }

        function verifyCode() {
            var inputCode = document.getElementById("verificationCode").value;
            var storedCode = sessionStorage.getItem("verificationCode");
            var email = document.forms["myForm"]["Email"].value;

            if (inputCode === storedCode) {
                // Send verification status to server
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "verify_email.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            sessionStorage.setItem("isVerified", "true");
                            alert("Email verified successfully!");
                            document.getElementById("verificationStatus").textContent = "✓ Email Verified";
                            document.getElementById("verificationStatus").style.color = "green";
                            document.getElementById("verificationCodeInput").style.display = "none";
                            document.getElementById("sendVerificationBtn").style.display = "none";
                        } else {
                            alert("Verification failed. Please try again.");
                        }
                    }
                };
                xhr.send("email=" + encodeURIComponent(email) + "&code=" + inputCode);
            } else {
                alert("Invalid verification code. Please try again.");
            }
            return false;
        }
    </script>
</head>
<body>
    <video class="video-background" muted loop autoplay style="width: 100%; height: 100%; object-fit: cover;">
        <source src="images/3196094-uhd_3840_2160_25fps.mp4" type="video/mp4">
    </video>

    <div class="form-container">
        <h3>Register Here</h3>
        <form action="regconn.php" method="post" name="myForm" id="registrationForm" onsubmit="return validateform();">
            <input type="text" id="Name" name="Name" placeholder="Name" required>
            <input type="text" id="Phone" name="Phone" placeholder="Phone" required>
            <input type="text" id="Email" name="Email" placeholder="Email" required>
            <input type="password" id="Pwd" name="Pwd" placeholder="Password" required>
            <button type="button" id="sendVerificationBtn" onclick="sendVerificationCode()">Send Verification Code</button>
            <div id="verificationCodeInput" style="display: none;">
                <input type="text" id="verificationCode" placeholder="Enter Verification Code">
                <button type="button" onclick="verifyCode()">Verify Code</button>
            </div>
            <div id="verificationStatus" style="margin: 10px 0;"></div>
            <button type="submit" name="save">Register</button>
        </form>
        <a href="login.php">Already have an account? Log-in</a>
    </div>
</body>
</html>