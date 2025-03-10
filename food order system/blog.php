<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Discover our food blog with fast delivery, free shipping, and the best quality food.">
    <meta name="keywords" content="food blog, fast delivery, free shipping, healthy food">
    <meta name="author" content="FoodHub">
    <title>FoodHub Blog</title>
    <link rel="shortcut icon" href="images/icon.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navigation Bar Styles - Keep as is */
        .topnav {
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.3);
        }

        .topnav-left {
            display: flex;
            align-items: center;
        }

        .topnav-right {
            display: flex;
            align-items: center;
        }

        .topnav a {
            color: white;
            text-decoration: none;
            padding: 0.8rem 1.5rem;
            margin: 0 0.3rem;
            border-radius: 25px;
            transition: all 0.3s ease;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .topnav a:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        .topnav a:hover:before {
            left: 100%;
        }

        .topnav a:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .topnav a.active {
            background: linear-gradient(45deg, #FF416C, #FF4B2B);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 65, 108, 0.3);
        }

        /* Mobile Navigation Styles */
        .menu-toggle {
            display: none;
            font-size: 1.5rem;
            color: white;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
        }

        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }

            .topnav-left, .topnav-right {
                display: none;
                width: 100%;
                flex-direction: column;
                background: rgba(0, 0, 0, 0.95);
                position: absolute;
                top: 100%;
                left: 0;
                padding: 1rem 0;
            }

            .topnav-left.active, .topnav-right.active {
                display: flex;
            }

            .topnav {
                flex-wrap: wrap;
                padding: 0.5rem 1rem;
            }

            .topnav a {
                width: 100%;
                text-align: center;
                padding: 1rem;
                margin: 0;
                border-radius: 0;
            }
        }

        /* Main Content Styles */
        .main-content {
            margin-top: 80px;
            flex: 1;
            padding: 2rem 0;
        }

        .inner_page_head {
            background: linear-gradient(135deg, #FF416C, #FF4B2B);
            padding: 3rem 0;
            margin-bottom: 3rem;
            text-align: center;
            color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .inner_page_head h3 {
            font-size: 2.5rem;
            font-weight: 600;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* Blog Posts Section */
        .blog-posts {
            padding: 2rem 0;
        }

        .blog-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .blog-card:hover {
            transform: translateY(-5px);
        }

        .blog-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .blog-content {
            padding: 2rem;
        }

        .blog-title {
            font-size: clamp(1.2rem, 2vw, 1.5rem);
            color: #333;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .blog-excerpt {
            font-size: clamp(0.9rem, 1.5vw, 1.1rem);
            color: #666;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .blog-meta {
            display: flex;
            align-items: center;
            color: #888;
            font-size: 0.9rem;
        }

        .blog-meta i {
            margin-right: 0.5rem;
        }

        .blog-meta span {
            margin-right: 1.5rem;
        }

        .read-more {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background: linear-gradient(45deg, #FF416C, #FF4B2B);
            color: white;
            border-radius: 25px;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .read-more:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 65, 108, 0.3);
            color: white;
        }

        /* Blog Cards Responsive */
        @media (max-width: 768px) {
            .blog-card {
                margin: 1rem 0;
            }

            .blog-image {
                height: 200px;
            }

            .blog-content {
                padding: 1.5rem;
            }

            .blog-title {
                font-size: 1.3rem;
            }

            .blog-meta {
                flex-wrap: wrap;
            }

            .blog-meta span {
                margin-bottom: 0.5rem;
            }
        }

        /* Why Choose Us Section */
        .why_section {
            padding: 4rem 0;
            background: white;
        }

        .heading_container {
            margin-bottom: 3rem;
        }

        .heading_container h3 {
            font-size: clamp(1.8rem, 3vw, 2.5rem);
            color: #333;
            font-weight: 600;
            position: relative;
            padding-bottom: 1rem;
        }

        .heading_container h3:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(45deg, #FF416C, #FF4B2B);
        }

        .box {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin: 1rem;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            height: 100%;
        }

        .box:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .img-box {
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .img-box svg {
            width: 60px;
            height: 60px;
            fill: #FF416C;
            transition: all 0.3s ease;
        }

        .box:hover .img-box svg {
            transform: scale(1.1);
            fill: #FF4B2B;
        }

        .detail-box h5 {
            font-size: 1.4rem;
            color: #333;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .detail-box p {
            font-size: 1.1rem;
            color: #666;
            line-height: 1.6;
        }

        /* Footer Section */
        .footer_section {
            background: #222;
            color: white;
            padding: 4rem 0 2rem;
            margin-top: auto;
        }

        .footer-col {
            margin-bottom: 2rem;
        }

        .footer_section h3 {
            color: white;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.5rem;
        }

        .footer_section h3:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 2px;
            background: #FF416C;
        }

        .contact_link_box a {
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .contact_link_box a:hover {
            color: #FF416C;
        }

        .contact_link_box i {
            margin-right: 1rem;
            font-size: 1.2rem;
        }

        .footer_social {
            margin-top: 1.5rem;
        }

        .footer_social a {
            color: white;
            font-size: 1.5rem;
            margin-right: 1.5rem;
            transition: all 0.3s ease;
        }

        .footer_social a:hover {
            color: #FF416C;
            transform: translateY(-3px);
        }

        .footer-info {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 2rem;
            padding-top: 2rem;
            text-align: center;
        }

        .footer-info p {
            color: #999;
            margin: 0;
        }

        /* Footer Responsive */
        @media (max-width: 768px) {
            .footer_section {
                padding: 2rem 0 1rem;
            }

            .footer-col {
                text-align: center;
            }

            .footer_social {
                justify-content: center;
                display: flex;
            }

            .contact_link_box {
                justify-content: center;
            }

            .contact_link_box a {
                justify-content: center;
            }
        }

        @media (max-width: 768px) {
            .box {
                margin: 1rem 0;
            }
            
            .inner_page_head {
                padding: 2rem 0;
            }
            
            .inner_page_head h3 {
                font-size: 2rem;
            }
            
            .why_section {
                padding: 2rem 0;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <div class="topnav">
        <button class="menu-toggle" aria-label="Toggle menu">
            <i class="fas fa-bars"></i>
        </button>
        <div class="topnav-left">
            <a href="home1.php">Home</a>
            <a href="aboutus.php">About Us</a>
            <a href="contact.php">Contact</a>
            <a class="active" href="blog.php">Blog</a>
        </div>
        <div class="topnav-right">
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="main-content">
        <!-- Blog Section -->
        <section class="inner_page_head">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="heading_container">
                            <h3>Our Food Blog</h3>
                            <p class="text-white">Discover delicious recipes and food stories</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Blog Posts -->
        <section class="blog-posts">
            <div class="container">
                <div class="row">
                    <!-- Blog Post 1 -->
                    <div class="col-md-4">
                        <div class="blog-card">
                            <img src="images/blog1.jpg" alt="Healthy Breakfast Ideas" class="blog-image">
                            <div class="blog-content">
                                <h3 class="blog-title">10 Healthy Breakfast Ideas</h3>
                                <p class="blog-excerpt">Start your day right with these nutritious and delicious breakfast recipes that are quick and easy to prepare.</p>
                                <div class="blog-meta">
                                    <span><i class="far fa-calendar"></i> June 15, 2023</span>
                                    <span><i class="far fa-user"></i> Chef Maria</span>
                                </div>
                                <a href="#" class="read-more">Read More</a>
                            </div>
                        </div>
                    </div>

                    <!-- Blog Post 2 -->
                    <div class="col-md-4">
                        <div class="blog-card">
                            <img src="images/blog2.jpg" alt="Summer Recipes" class="blog-image">
                            <div class="blog-content">
                                <h3 class="blog-title">Summer Fresh Recipes</h3>
                                <p class="blog-excerpt">Beat the heat with these refreshing summer recipes perfect for outdoor gatherings and picnics.</p>
                                <div class="blog-meta">
                                    <span><i class="far fa-calendar"></i> June 12, 2023</span>
                                    <span><i class="far fa-user"></i> Chef John</span>
                                </div>
                                <a href="#" class="read-more">Read More</a>
                            </div>
                        </div>
                    </div>

                    <!-- Blog Post 3 -->
                    <div class="col-md-4">
                        <div class="blog-card">
                            <img src="images/blog3.jpg" alt="Dessert Recipes" class="blog-image">
                            <div class="blog-content">
                                <h3 class="blog-title">Decadent Desserts</h3>
                                <p class="blog-excerpt">Indulge in these mouthwatering dessert recipes that will satisfy any sweet tooth craving.</p>
                                <div class="blog-meta">
                                    <span><i class="far fa-calendar"></i> June 10, 2023</span>
                                    <span><i class="far fa-user"></i> Chef Sarah</span>
                                </div>
                                <a href="#" class="read-more">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why Choose Us Section -->
        <section class="why_section">
            <div class="container">
                <div class="heading_container heading_center">
                    <h3>Why Choose Us?</h3>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="box">
                            <div class="img-box">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <path d="M476.158,231.363l-13.259-53.035c3.625-0.77,6.345-3.986,6.345-7.839v-8.551c0-18.566-15.105-33.67-33.67-33.67h-60.392V110.63c0-9.136-7.432-16.568-16.568-16.568H50.772c-9.136,0-16.568,7.432-16.568,16.568V256c0,4.427,3.589,8.017,8.017,8.017c4.427,0,8.017-3.589,8.017-8.017V110.63c0-0.295,0.239-0.534,0.534-0.534h307.841c0.295,0,0.534,0.239,0.534,0.534v145.372c0,4.427,3.589,8.017,8.017,8.017c4.427,0,8.017-3.589,8.017-8.017v-9.088h94.569c0.008,0,0.014,0.002,0.021,0.002c0.008,0,0.015-0.001,0.022-0.001c11.637,0.008,21.518,7.646,24.912,18.171h-24.928c-4.427,0-8.017,3.589-8.017,8.017v17.102c0,13.851,11.268,25.119,25.119,25.119h9.086v35.273h-20.962c-6.886-19.883-25.787-34.205-47.982-34.205s-41.097,14.322-47.982,34.205h-3.86v-60.393c0-4.427-3.589-8.017-8.017-8.017c-4.427,0-8.017,3.589-8.017,8.017v60.391H192.817c-6.886-19.883-25.787-34.205-47.982-34.205s-41.097,14.322-47.982,34.205H50.772c-0.295,0-0.534-0.239-0.534-0.534v-17.637h34.739c4.427,0,8.017-3.589,8.017-8.017s-3.589-8.017-8.017-8.017H8.017c-4.427,0-8.017,3.589-8.017,8.017s3.589,8.017,8.017,8.017h26.188v17.637c0,9.136,7.432,16.568,16.568,16.568h43.304c-0.002,0.178-0.014,0.355-0.014,0.534c0,27.996,22.777,50.772,50.772,50.772s50.772-22.776,50.772-50.772c0-0.18-0.012-0.356-0.014-0.534h180.67c-0.002,0.178-0.014,0.355-0.014,0.534c0,27.996,22.777,50.772,50.772,50.772c27.995,0,50.772-22.776,50.772-50.772c0-0.18-0.012-0.356-0.014-0.534h26.203c4.427,0,8.017-3.589,8.017-8.017v-85.511C512,251.989,496.423,234.448,476.158,231.363zM375.182,144.301h60.392c9.725,0,17.637,7.912,17.637,17.637v0.534h-78.029V144.301zM375.182,230.881v-52.376h71.235l13.094,52.376H375.182zM144.835,401.904c-19.155,0-34.739-15.583-34.739-34.739s15.584-34.739,34.739-34.739c19.155,0,34.739,15.583,34.739,34.739S163.99,401.904,144.835,401.904zM427.023,401.904c-19.155,0-34.739-15.583-34.739-34.739s15.584-34.739,34.739-34.739c19.155,0,34.739,15.583,34.739,34.739S446.178,401.904,427.023,401.904zM495.967,299.29h-9.086c-5.01,0-9.086-4.076-9.086-9.086v-9.086h18.171V299.29z" />
                                </svg>
                            </div>
                            <div class="detail-box">
                                <h5>Fast Delivery</h5>
                                <p>We deliver the food in just <br><b>30 Minutes</b></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="box">
                            <div class="img-box">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 490.667 490.667">
                                    <path d="M138.667,192H96c-5.888,0-10.667,4.779-10.667,10.667V288c0,5.888,4.779,10.667,10.667,10.667s10.667-4.779,10.667-10.667v-74.667h32c5.888,0,10.667-4.779,10.667-10.667S144.555,192,138.667,192z" />
                                    <path d="M117.333,234.667H96c-5.888,0-10.667,4.779-10.667,10.667S90.112,256,96,256h21.333c5.888,0,10.667-4.779,10.667-10.667S123.221,234.667,117.333,234.667z" />
                                    <path d="M245.333,0C110.059,0,0,110.059,0,245.333s110.059,245.333,245.333,245.333s245.333-110.059,245.333-245.333S380.608,0,245.333,0zM245.333,469.333c-123.52,0-224-100.48-224-224s100.48-224,224-224s224,100.48,224,224S368.853,469.333,245.333,469.333z" />
                                    <path d="M386.752 131.989C352.085 88.789 300.544 64 245.333 64s-106.752 24.789-141.419 67.989c-3.691 4.587-2.965 11.307 1.643 14.997c4.587 3.691 11.307 2.965 14.976-1.643c30.613-38.144 76.096-60.011 124.8-60.011s94.187 21.867 124.779 60.011c2.112 2.624 5.205 3.989 8.32 3.989c2.368 0 4.715-0.768 6.677-2.347C389.717 143.296 390.443 136.576 386.752 131.989z" />
                                    <path d="M376.405 354.923c-4.224-4.032-11.008-3.861-15.061 0.405c-30.613 32.235-71.808 50.005-116.011 50.005s-85.397-17.771-115.989-50.005c-4.032-4.309-10.816-4.437-15.061-0.405c-4.309 4.053-4.459 10.816-0.405 15.083c34.667 36.544 81.344 56.661 131.456 56.661s96.789-20.117 131.477-56.661C380.864 365.739 380.693 358.976 376.405 354.923z" />
                                    <path d="M206.805 255.723c15.701-2.027 27.861-15.488 27.861-31.723c0-17.643-14.357-32-32-32h-21.333c-5.888 0-10.667 4.779-10.667 10.667v42.581c0 0.043 0 0.107 0 0.149V288c0 5.888 4.779 10.667 10.667 10.667S192 293.888 192 288v-16.917l24.448 24.469c2.091 2.069 4.821 3.115 7.552 3.115c2.731 0 5.461-1.045 7.531-3.136c4.16-4.16 4.16-10.923 0-15.083L206.805 255.723zM192 234.667v-21.333h10.667c5.867 0 10.667 4.779 10.667 10.667s-4.8 10.667-10.667 10.667H192z" />
                                    <path d="M309.333 277.333h-32v-64h32c5.888 0 10.667-4.779 10.667-10.667S315.221 192 309.333 192h-42.667c-5.888 0-10.667 4.779-10.667 10.667V288c0 5.888 4.779 10.667 10.667 10.667h42.667c5.888 0 10.667-4.779 10.667-10.667S315.221 277.333 309.333 277.333z" />
                                    <path d="M288 234.667h-21.333c-5.888 0-10.667 4.779-10.667 10.667S260.779 256 266.667 256H288c5.888 0 10.667-4.779 10.667-10.667S293.888 234.667 288 234.667z" />
                                    <path d="M394.667 277.333h-32v-64h32c5.888 0 10.667-4.779 10.667-10.667S400.555 192 394.667 192H352c-5.888 0-10.667 4.779-10.667 10.667V288c0 5.888 4.779 10.667 10.667 10.667h42.667c5.888 0 10.667-4.779 10.667-10.667S400.555 277.333 394.667 277.333z" />
                                    <path d="M373.333 234.667H352c-5.888 0-10.667 4.779-10.667 10.667S346.112 256 352 256h21.333c5.888 0 10.667-4.779 10.667-10.667S379.221 234.667 373.333 234.667z" />
                                </svg>
                            </div>
                            <div class="detail-box">
                                <h5>Free Shipping</h5>
                                <p>There is <b>No Delivery Charge</b> on food shipping</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="box">
                            <div class="img-box">
                                <svg id="_30_Premium" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M252.92 300h3.08a124.245 124.245 0 1 0 -4.49-.09c.075.009.15.023.226.03.394.039.789.06 1.184.06zm-96.92-124a100 100 0 1 1 100 100 100.113 100.113 0 0 1 -100-100z" />
                                    <path d="M386.752 131.989C352.085 88.789 300.544 64 245.333 64s-106.752 24.789-141.419 67.989c-3.691 4.587-2.965 11.307 1.643 14.997c4.587 3.691 11.307 2.965 14.976-1.643c30.613-38.144 76.096-60.011 124.8-60.011s94.187 21.867 124.779 60.011c2.112 2.624 5.205 3.989 8.32 3.989c2.368 0 4.715-0.768 6.677-2.347C389.717 143.296 390.443 136.576 386.752 131.989z" />
                                    <path d="M376.405 354.923c-4.224-4.032-11.008-3.861-15.061 0.405c-30.613 32.235-71.808 50.005-116.011 50.005s-85.397-17.771-115.989-50.005c-4.032-4.309-10.816-4.437-15.061-0.405c-4.309 4.053-4.459 10.816-0.405 15.083c34.667 36.544 81.344 56.661 131.456 56.661s96.789-20.117 131.477-56.661C380.864 365.739 380.693 358.976 376.405 354.923z" />
                                    <path d="M206.805 255.723c15.701-2.027 27.861-15.488 27.861-31.723c0-17.643-14.357-32-32-32h-21.333c-5.888 0-10.667 4.779-10.667 10.667v42.581c0 0.043 0 0.107 0 0.149V288c0 5.888 4.779 10.667 10.667 10.667S192 293.888 192 288v-16.917l24.448 24.469c2.091 2.069 4.821 3.115 7.552 3.115c2.731 0 5.461-1.045 7.531-3.136c4.16-4.16 4.16-10.923 0-15.083L206.805 255.723zM192 234.667v-21.333h10.667c5.867 0 10.667 4.779 10.667 10.667s-4.8 10.667-10.667 10.667H192z" />
                                    <path d="M309.333 277.333h-32v-64h32c5.888 0 10.667-4.779 10.667-10.667S315.221 192 309.333 192h-42.667c-5.888 0-10.667 4.779-10.667 10.667V288c0 5.888 4.779 10.667 10.667 10.667h42.667c5.888 0 10.667-4.779 10.667-10.667S315.221 277.333 309.333 277.333z" />
                                    <path d="M288 234.667h-21.333c-5.888 0-10.667 4.779-10.667 10.667S260.779 256 266.667 256H288c5.888 0 10.667-4.779 10.667-10.667S293.888 234.667 288 234.667z" />
                                    <path d="M394.667 277.333h-32v-64h32c5.888 0 10.667-4.779 10.667-10.667S400.555 192 394.667 192H352c-5.888 0-10.667 4.779-10.667 10.667V288c0 5.888 4.779 10.667 10.667 10.667h42.667c5.888 0 10.667-4.779 10.667-10.667S400.555 277.333 394.667 277.333z" />
                                    <path d="M373.333 234.667H352c-5.888 0-10.667 4.779-10.667 10.667S346.112 256 352 256h21.333c5.888 0 10.667-4.779 10.667-10.667S379.221 234.667 373.333 234.667z" />
                                </svg>
                            </div>
                            <div class="detail-box">
                                <h5>Best Quality Food</h5>
                                <p>We use organic vegetables and serve <b>Fresh And Healthy</b> food</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer Section -->
    <footer class="footer_section">
        <div class="container">
            <div class="row">
                <div class="col-md-4 footer-col">
                    <div class="footer_contact">
                        <h3>Reach Us</h3>
                        <div class="contact_link_box">
                            <a href="https://goo.gl/maps/HeHvCRZDzLco8ChX8" target="_blank">
                                <i class="fas fa-location-arrow"></i>
                                <span>Location</span>
                            </a>
                            <a href="tel:+911234567890">
                                <i class="fas fa-phone-volume"></i>
                                <span>Call +91 1234567890</span>
                            </a>
                            <a href="mailto:foodhub@gmail.com" target="_blank">
                                <i class="fas fa-envelope"></i>
                                <span>Foodhub@gmail.com</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 footer-col">
                    <div class="footer_detail">
                        <h3>About Us</h3>
                        <p>We are serving food to customers for over 5 years, and all our customers are happy with our service.</p>
                        <div class="footer_social">
                            <a href="https://www.facebook.com/Foodhub.co.uk/" target="_blank">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/FoodhubUK" target="_blank">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://www.linkedin.com/company/foodhubuk" target="_blank">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="https://www.instagram.com/foodhub.co.uk/" target="_blank">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 footer-col">
                    <div class="map_container">
                        <div class="map">
                            <div id="googleMap"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-info">
                <div class="col-lg-7 mx-auto px-0">
                    <p>&copy; <span id="displayYear"></span> All Rights Reserved By FoodHub<br>Distributed By Funfoods</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/custom.js"></script>
    <script>
        document.getElementById('displayYear').textContent = new Date().getFullYear();

        // Mobile Navigation
        const menuToggle = document.querySelector('.menu-toggle');
        const topnavLeft = document.querySelector('.topnav-left');
        const topnavRight = document.querySelector('.topnav-right');

        menuToggle.addEventListener('click', () => {
            topnavLeft.classList.toggle('active');
            topnavRight.classList.toggle('active');
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.topnav')) {
                topnavLeft.classList.remove('active');
                topnavRight.classList.remove('active');
            }
        });
    </script>
</body>
</html>