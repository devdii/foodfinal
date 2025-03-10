<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - FoodHub</title>
    <link rel="shortcut icon" type="image/png" href="images/icon.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(45deg, #FF416C, #FF4B2B);
            --text-color: #333;
            --nav-bg: rgba(0, 0, 0, 0.9);
            --white: #ffffff;
            --error-color: #dc3545;
            --success-color: #28a745;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f4f4f4;
            color: var(--text-color);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .topnav {
            background: var(--nav-bg);
            backdrop-filter: blur(10px);
            padding: 0.8rem;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.3);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 1rem;
        }

        .topnav-left, .topnav-right {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--white);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
        }

        .topnav a {
            color: var(--white);
            text-decoration: none;
            padding: 0.6rem 1rem;
            border-radius: 25px;
            transition: all 0.3s ease;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
        }

        .topnav a:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .topnav a.active {
            background: var(--primary-gradient);
            box-shadow: 0 4px 15px rgba(255, 65, 108, 0.3);
        }

        .video-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            z-index: -1;
        }

        .video-container video {
            position: absolute;
            min-width: 100%;
            min-height: 100%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            object-fit: cover;
            filter: brightness(0.5);
        }

        .contact-section {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            padding-top: 100px;
        }

        .contact-form {
            background: rgba(255, 255, 255, 0.95);
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
            animation: slideUp 0.5s ease-out;
            margin: 1rem;
        }

        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .contact-form h3 {
            font-size: clamp(1.5rem, 4vw, 2rem);
            color: var(--text-color);
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .contact-form p {
            text-align: center;
            color: #666;
            margin-bottom: 1.5rem;
            font-size: clamp(0.9rem, 2vw, 1rem);
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-group i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            font-size: clamp(0.9rem, 2vw, 1rem);
        }

        .form-control {
            width: 100%;
            padding: 0.8rem;
            padding-left: 2.8rem;
            border: 2px solid #e1e1e1;
            border-radius: 10px;
            font-size: clamp(0.9rem, 2vw, 1rem);
            transition: all 0.3s ease;
            background: var(--white);
        }

        .form-control:focus {
            border-color: #FF416C;
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 65, 108, 0.1);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .submit-btn {
            width: 100%;
            padding: 0.8rem;
            border: none;
            border-radius: 10px;
            background: var(--primary-gradient);
            color: var(--white);
            font-size: clamp(0.9rem, 2vw, 1rem);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 65, 108, 0.3);
        }

        .error-message {
            color: var(--error-color);
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: none;
        }

        .form-control.error {
            border-color: var(--error-color);
        }

        .form-control.success {
            border-color: var(--success-color);
        }

        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }

            .nav-container {
                position: relative;
            }

            .topnav-left, .topnav-right {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background: var(--nav-bg);
                flex-direction: column;
                padding: 1rem;
                gap: 0.8rem;
                border-radius: 0 0 10px 10px;
            }

            .topnav-left.show, .topnav-right.show {
                display: flex;
            }

            .topnav a {
                width: 100%;
                justify-content: center;
            }

            .contact-section {
                padding: 1rem;
                padding-top: 80px;
            }

            .contact-form {
                padding: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .contact-form {
                margin: 0.5rem;
                padding: 1.2rem;
            }

            .form-control {
                padding: 0.7rem;
                padding-left: 2.5rem;
            }

            .form-group i {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <nav class="topnav">
        <div class="nav-container">
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>
            <div class="topnav-left" id="navLeft">
                <a href="home1.php"><i class="fas fa-home"></i> Home</a>
                <a href="aboutus.php"><i class="fas fa-info-circle"></i> About Us</a>
                <a class="active" href="contact.php"><i class="fas fa-envelope"></i> Contact</a>
                <a href="blog.php"><i class="fas fa-blog"></i> Blog</a>
            </div>
            <div class="topnav-right" id="navRight">
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </nav>

    <div class="video-container">
        <video muted loop autoplay playsinline>
            <source src="images/2431225-uhd_3840_2160_24fps.mp4" type="video/mp4">
        </video>
    </div>

    <main class="contact-section">
        <div class="contact-form">
            <h3>Get in Touch</h3>
            <p>We'd love to hear from you. Send us a message!</p>
            
            <form action="regcon.php" method="post" id="contactForm" onsubmit="return validateForm(event)">
                <div class="form-group">
                    <i class="fas fa-user"></i>
                    <input type="text" class="form-control" id="Name" name="Name" placeholder="Your Name">
                    <div class="error-message" id="nameError"></div>
                </div>

                <div class="form-group">
                    <i class="fas fa-phone"></i>
                    <input type="tel" class="form-control" id="Phone" name="Phone" placeholder="Phone Number">
                    <div class="error-message" id="phoneError"></div>
                </div>

                <div class="form-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" class="form-control" id="Email" name="Email" placeholder="Email Address">
                    <div class="error-message" id="emailError"></div>
                </div>

                <div class="form-group">
                    <i class="fas fa-comment-alt"></i>
                    <textarea class="form-control" id="message" name="message" placeholder="Your Message"></textarea>
                    <div class="error-message" id="messageError"></div>
                </div>

                <button type="submit" name="save" class="submit-btn">
                    <i class="fas fa-paper-plane"></i> Send Message
                </button>
            </form>
        </div>
    </main>

    <script>
        // Toggle menu functionality
        const menuToggle = document.getElementById('menuToggle');
        const navLeft = document.getElementById('navLeft');
        const navRight = document.getElementById('navRight');

        menuToggle.addEventListener('click', () => {
            navLeft.classList.toggle('show');
            navRight.classList.toggle('show');
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.nav-container')) {
                navLeft.classList.remove('show');
                navRight.classList.remove('show');
            }
        });

        function validateForm(event) {
            event.preventDefault();
            let isValid = true;
            
            // Reset previous errors
            document.querySelectorAll('.form-control').forEach(input => {
                input.classList.remove('error', 'success');
            });
            document.querySelectorAll('.error-message').forEach(error => {
                error.style.display = 'none';
            });

            // Name validation
            const name = document.getElementById('Name');
            if (!name.value.trim()) {
                showError(name, 'nameError', 'Name is required');
                isValid = false;
            } else if (name.value.trim().length < 2) {
                showError(name, 'nameError', 'Name must be at least 2 characters');
                isValid = false;
            } else {
                showSuccess(name);
            }

            // Phone validation
            const phone = document.getElementById('Phone');
            const phoneRegex = /^[0-9]{10}$/;
            if (!phone.value.trim()) {
                showError(phone, 'phoneError', 'Phone number is required');
                isValid = false;
            } else if (!phoneRegex.test(phone.value.trim())) {
                showError(phone, 'phoneError', 'Please enter a valid 10-digit phone number');
                isValid = false;
            } else {
                showSuccess(phone);
            }

            // Email validation
            const email = document.getElementById('Email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email.value.trim()) {
                showError(email, 'emailError', 'Email is required');
                isValid = false;
            } else if (!emailRegex.test(email.value.trim())) {
                showError(email, 'emailError', 'Please enter a valid email address');
                isValid = false;
            } else {
                showSuccess(email);
            }

            // Message validation
            const message = document.getElementById('message');
            if (!message.value.trim()) {
                showError(message, 'messageError', 'Message is required');
                isValid = false;
            } else if (message.value.trim().length < 10) {
                showError(message, 'messageError', 'Message must be at least 10 characters');
                isValid = false;
            } else {
                showSuccess(message);
            }

            if (isValid) {
                document.getElementById('contactForm').submit();
            }
        }

        function showError(input, errorId, message) {
            input.classList.add('error');
            const errorDiv = document.getElementById(errorId);
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
        }

        function showSuccess(input) {
            input.classList.add('success');
        }
    </script>
</body>
</html>