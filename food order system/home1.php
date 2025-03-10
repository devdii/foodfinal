<?php
require_once 'auth.php';

// Check authentication and session timeout
checkAuth();
if (checkSessionTimeout()) {
    header('Location: login.php?msg=session_expired');
    exit();
}

// Get user information
$userEmail = getUserEmail();
$userRole = getUserRole();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FoodHub - Home</title>
    <link rel="shortcut icon" type="image/png" href="images/icon.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(45deg, #FF416C, #FF4B2B);
            --text-color: #ffffff;
            --nav-bg: rgba(0, 0, 0, 0.9);
            --overlay-bg: rgba(0, 0, 0, 0.6);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            overflow-x: hidden;
            color: var(--text-color);
        }

        .video-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .video-container video {
            position: absolute;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            object-fit: cover;
            filter: brightness(0.5);
            animation: fadeIn 1s ease-in-out;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--overlay-bg);
            z-index: 1;
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
            position: relative;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--text-color);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
        }

        .topnav-left, .topnav-right {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .topnav a {
            color: var(--text-color);
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

        .user-welcome {
            color: var(--text-color);
            margin-right: 1rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
        }

        .hero-section {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: clamp(1rem, 4vw, 2rem);
            position: relative;
            z-index: 2;
            margin-top: clamp(0px, 4vw, 40px);
        }

        .hero-title {
            font-size: clamp(2rem, 8vw, 4.5rem);
            font-weight: 700;
            margin-bottom: clamp(1rem, 4vw, 2rem);
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: titleFloat 3s ease-in-out infinite;
            text-align: center;
            max-width: 90%;
        }

        .hero-subtitle {
            font-size: clamp(0.9rem, 3vw, 1.5rem);
            margin-bottom: clamp(2rem, 6vw, 3rem);
            max-width: min(600px, 90%);
            opacity: 0.9;
            line-height: 1.6;
        }

        .cta-container {
            display: flex;
            gap: clamp(0.5rem, 2vw, 1rem);
            flex-wrap: wrap;
            justify-content: center;
            width: 100%;
            max-width: 600px;
            padding: 0 1rem;
        }

        .cta-button {
            padding: clamp(0.8rem, 2vw, 1rem) clamp(1.5rem, 4vw, 2rem);
            font-size: clamp(0.9rem, 2vw, 1.1rem);
            font-weight: 600;
            border: none;
            border-radius: 50px;
            background: var(--primary-gradient);
            color: var(--text-color);
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 10px 20px rgba(255, 65, 108, 0.3);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            min-width: 200px;
            justify-content: center;
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(255, 65, 108, 0.4);
        }

        .cta-button.secondary {
            background: transparent;
            border: 2px solid #FF416C;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes titleFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
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

            .topnav a, .user-welcome {
                width: 100%;
                justify-content: center;
                margin: 0;
                padding: 0.8rem;
            }

            .hero-section {
                padding-top: clamp(80px, 15vh, 120px);
            }

            .cta-container {
                flex-direction: column;
                align-items: stretch;
            }

            .cta-button {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .hero-section {
                padding: 1rem;
                padding-top: 80px;
            }

            .hero-title {
                font-size: clamp(1.8rem, 6vw, 2.5rem);
            }

            .hero-subtitle {
                font-size: clamp(0.8rem, 2.5vw, 1rem);
            }

            .cta-button {
                padding: 0.8rem 1.5rem;
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
                <a href="home1.php" class="active"><i class="fas fa-home"></i> Home</a>
                <a href="aboutus.php"><i class="fas fa-info-circle"></i> About</a>
                <a href="contact.php"><i class="fas fa-envelope"></i> Contact</a>
                <a href="blog.php"><i class="fas fa-blog"></i> Blog</a>
                <?php if ($userRole === 'admin'): ?>
                    <a href="admin_dashboard.php"><i class="fas fa-user-shield"></i> Admin</a>
                <?php endif; ?>
            </div>
            <div class="topnav-right" id="navRight">
                <div class="user-welcome">
                    <i class="fas fa-user-circle"></i>
                    Welcome, <?php echo htmlspecialchars($userEmail); ?>
                </div>
                <a href="profile.php"><i class="fas fa-user-cog"></i> Profile</a>
                <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </nav>
    
    <div class="video-container">
        <video muted loop autoplay playsinline>
            <source src="images/3196094-uhd_3840_2160_25fps.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
    <div class="overlay"></div>
    
    <main class="hero-section">
        <h1 class="hero-title">Welcome to FoodHub</h1>
        <p class="hero-subtitle">Experience the finest culinary delights delivered right to your doorstep</p>
        <div class="cta-container">
            <a href="products.php" class="cta-button">
                <i class="fas fa-utensils"></i> Explore Menu
            </a>
            <a href="aboutus.php" class="cta-button secondary">
                <i class="fas fa-info-circle"></i> Learn More
            </a>
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
    </script>
</body>
</html>