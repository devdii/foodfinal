<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Food Hub</title>
    <link rel="shortcut icon" type="image/png" href="images/icon.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #FF416C;
            --secondary-color: #FFDD57;
            --text-color: #ffffff;
            --bg-overlay: rgba(0, 0, 0, 0.8);
            --card-bg: rgba(71, 78, 93, 0.9);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background: linear-gradient(var(--bg-overlay), var(--bg-overlay)), url("images/1.jpg") center/cover fixed;
            min-height: 100vh;
            padding-top: 80px;
            overflow-x: hidden;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Navigation */
        .topnav {
            background: rgba(0, 0, 0, 0.95);
            backdrop-filter: blur(10px);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 0.8rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
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

        .topnav a:hover:not(.active) {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .topnav a.active {
            background: linear-gradient(45deg, var(--primary-color), #FF4B2B);
            box-shadow: 0 4px 15px rgba(255, 65, 108, 0.3);
        }

        /* Hero Section */
        .hero {
            text-align: center;
            padding: clamp(2rem, 4vw, 4rem) clamp(1rem, 2vw, 2rem);
            background: linear-gradient(135deg, var(--card-bg), rgba(71, 78, 93, 0.7));
            border-radius: 20px;
            margin-bottom: 3rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hero h1 {
            font-size: clamp(1.8rem, 4vw, 2.5rem);
            color: var(--secondary-color);
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .hero p {
            font-size: clamp(0.9rem, 2vw, 1.1rem);
            max-width: 800px;
            margin: 1rem auto;
            opacity: 0.9;
        }

        /* Team Section */
        .team-section {
            padding: clamp(2rem, 4vw, 4rem) 0;
        }

        .section-title {
            text-align: center;
            font-size: clamp(1.8rem, 3vw, 2rem);
            color: var(--secondary-color);
            margin-bottom: 3rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            padding: 1rem;
        }

        .team-card {
            background: var(--card-bg);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .team-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .team-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .team-card:hover img {
            transform: scale(1.05);
        }

        .team-info {
            padding: 1.5rem;
        }

        .team-info h3 {
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
            font-size: clamp(1.1rem, 2vw, 1.3rem);
        }

        .team-info p {
            font-size: clamp(0.9rem, 1.5vw, 1rem);
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
            justify-content: center;
        }

        .social-links a {
            color: var(--text-color);
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            color: var(--secondary-color);
            transform: translateY(-3px);
        }

        /* Reviews Section */
        .reviews-section {
            background: linear-gradient(135deg, var(--card-bg), rgba(71, 78, 93, 0.7));
            padding: clamp(2rem, 4vw, 4rem);
            border-radius: 20px;
            margin-top: 3rem;
        }

        .reviews-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .review-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 1.5rem;
            border-radius: 15px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .review-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.15);
        }

        .review-card img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 1rem;
            object-fit: cover;
            border: 3px solid var(--secondary-color);
        }

        .review-card p {
            font-style: italic;
            margin: 1rem 0;
            opacity: 0.9;
            font-size: clamp(0.9rem, 1.5vw, 1rem);
        }

        .review-card h3 {
            color: var(--secondary-color);
            font-size: clamp(1rem, 1.8vw, 1.2rem);
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
                background: rgba(0, 0, 0, 0.95);
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

            .container {
                padding: 10px;
            }

            .team-grid, .reviews-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .team-card img {
                height: 200px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding-top: 60px;
            }

            .container {
                width: 95%;
                padding: 10px 5px;
            }

            .hero {
                padding: 1.5rem 1rem;
            }

            .team-card {
                margin: 0 0.5rem;
            }

            .reviews-section {
                padding: 1.5rem 1rem;
            }

            .review-card {
                padding: 1rem;
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
                <a class="active" href="aboutus.php"><i class="fas fa-info-circle"></i> About Us</a>
                <a href="contact.php"><i class="fas fa-envelope"></i> Contact</a>
                <a href="blog.php"><i class="fas fa-blog"></i> Blog</a>
            </div>
            <div class="topnav-right" id="navRight">
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <section class="hero">
            <h1><i class="fas fa-utensils"></i> Welcome to Food Hub</h1>
            <p>Food Hub provides fast food delivery to customers with unmatched quality and service.</p>
            <p>Every day we strive to improve our quality and give our best to our valued customers.</p>
            <hr style="margin: 2rem 0; opacity: 0.2;">
            <p>Welcome to our online food delivery website, where we bring the finest culinary experiences to your doorstep. Our mission is to provide you with the best quality food and exceptional service, so that you can enjoy delicious meals from the comfort of your home.</p>
            <p>Our team is passionate about food and we take great pride in curating a diverse menu that caters to different taste preferences. We work with top-rated restaurants and chefs to bring you an extensive selection of dishes ranging from traditional to contemporary cuisine.</p>
            <p>At Food Hub, we understand the importance of convenience and speed. That's why we have created a user-friendly platform that allows you to easily browse our menu and place your order. We use the latest technology to ensure that the vegetables are fresh and your food arrives hot every time.</p>
        </section>

        <section class="team-section">
            <h2 class="section-title"><i class="fas fa-users"></i> Our Team</h2>
            <div class="team-grid">
                <div class="team-card">
                    <img src="images/ceo.jpg" alt="Founder">
                    <div class="team-info">
                        <h3>Atul Upadhyay</h3>
                        <p><strong>Founder</strong></p>
                        <p>We use eco-friendly packaging and implement practices to minimize food waste.</p>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
                <div class="team-card">
                    <img src="images/man.jpg" alt="Restaurant Manager">
                    <div class="team-info">
                        <h3>David Alex</h3>
                        <p><strong>Restaurant Manager</strong></p>
                        <p>I have over 15 years of experience. I like to manage the restaurant and give my best.</p>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
                <div class="team-card">
                    <img src="images/chef.jpg" alt="Executive Chef">
                    <div class="team-info">
                        <h3>Shrutika Koli</h3>
                        <p><strong>Executive Chef</strong></p>
                        <p>I make food as sweet as my nature. I like to serve sweetness to people.</p>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="reviews-section">
            <h2 class="section-title"><i class="fas fa-star"></i> Customer Reviews</h2>
            <div class="reviews-grid">
                <div class="review-card">
                    <img src="images/per2.jpg" alt="Martin David">
                    <p>"This is the best food delivery restaurant in this area."</p>
                    <h3>Martin David</h3>
                </div>
                <div class="review-card">
                    <img src="images/per1.jpg" alt="Flora Miller">
                    <p>"I am so happy that the fastest food was delivered in just 8 minutes."</p>
                    <h3>Flora Miller</h3>
                </div>
                <div class="review-card">
                    <img src="images/per4.jpg" alt="Mayur Patel">
                    <p>"Every time they serve fresh food and provide better service compared to others."</p>
                    <h3>Mayur Patel</h3>
                </div>
                <div class="review-card">
                    <img src="images/per6.jpg" alt="Olly Jenith">
                    <p>"They deliver food in paper bags, which is nice because they are eco-friendly."</p>
                    <h3>Olly Jenith</h3>
                </div>
                <div class="review-card">
                    <img src="images/per3.jpg" alt="Gllen Smith">
                    <p>"The best food in their restaurant is Red-Cheese Pizza."</p>
                    <h3>Gllen Smith</h3>
                </div>
            </div>
        </section>
    </div>

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