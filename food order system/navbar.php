<?php
require_once 'auth.php';

$userEmail = getUserEmail();
$userRole = getUserRole();

// Get cart count
@include 'config.php';
$sessionid = session_id();
$cart_count_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM `cart` WHERE sessionid = '$sessionid'");
$cart_count = mysqli_fetch_assoc($cart_count_query)['count'];

// Get active orders count
$orders_count_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM `corder` WHERE sessionid = '$sessionid' AND status != 'delivered'");
$active_orders = mysqli_fetch_assoc($orders_count_query)['count'];
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="home1.php">FoodHub</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="home1.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="products.php">Menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="aboutus.php">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="blog.php">Blog</a>
                </li>
                <?php if ($userRole === 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="admin_dashboard.php">Admin</a>
                </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <?php if ($active_orders > 0): ?>
                <li class="nav-item">
                    <a class="nav-link" href="order_status.php">
                        <i class="fas fa-clock"></i> Track Orders
                        <span class="badge badge-warning"><?php echo $active_orders; ?></span>
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link cart-link" href="cart.php">
                        <i class="fas fa-shopping-cart"></i>
                        <?php if ($cart_count > 0): ?>
                        <span class="cart-count"><?php echo $cart_count; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo htmlspecialchars($userEmail); ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="profile.php">Profile</a>
                        <a class="dropdown-item" href="order_status.php">My Orders</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="logout.php">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
.navbar {
    padding: 1rem;
    box-shadow: 0 2px 4px rgba(0,0,0,.1);
}

.cart-link {
    position: relative;
}

.cart-count {
    position: absolute;
    top: 0;
    right: 0;
    background: #dc3545;
    color: white;
    border-radius: 50%;
    padding: 0.2rem 0.5rem;
    font-size: 0.8rem;
}

.badge-warning {
    color: #212529;
    background-color: #ffc107;
    margin-left: 5px;
}

.navbar-nav .nav-item {
    margin: 0 5px;
}

.dropdown-menu {
    border: none;
    box-shadow: 0 2px 5px rgba(0,0,0,.1);
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}
</style>

<script>
// Add active class to current page
document.addEventListener('DOMContentLoaded', function() {
    const currentPage = window.location.pathname.split('/').pop();
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href === currentPage) {
            link.classList.add('active');
        }
    });
});
</script>
