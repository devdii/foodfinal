<?php
require_once 'auth.php';

// Check authentication and session timeout
checkAuth();
if (checkSessionTimeout()) {
    header('Location: login.php?msg=session_expired');
    exit();
}

@include 'config.php';

// Get current session ID
$sessionid = session_id();

// Get cart count
$cart_count_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM `cart` WHERE sessionid = '$sessionid'");
$cart_count = mysqli_fetch_assoc($cart_count_query)['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Menu - FoodHub</title>
   <link rel="shortcut icon" type="image/png" href="images/icon.png">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
   <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
   <style>
      body {
         font-family: 'Poppins', sans-serif;
         background-color: #f8f9fa;
         padding-top: 80px;
      }
      .search-container {
         margin: 20px auto;
         max-width: 600px;
         position: relative;
      }
      .search-input {
         width: 100%;
         padding: 15px 20px;
         border: 2px solid #ddd;
         border-radius: 30px;
         font-size: 16px;
         transition: all 0.3s ease;
      }
      .search-input:focus {
         border-color: #ff6b6b;
         box-shadow: 0 0 10px rgba(255, 107, 107, 0.2);
         outline: none;
      }
      .search-icon {
         position: absolute;
         right: 20px;
         top: 50%;
         transform: translateY(-50%);
         color: #666;
         font-size: 18px;
      }
      .products {
         display: grid;
         grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
         gap: 30px;
         padding: 20px;
      }
      .product-card {
         background: white;
         border-radius: 15px;
         box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
         transition: transform 0.3s ease;
         overflow: hidden;
         display: flex;
         flex-direction: column;
      }
      .product-card:hover {
         transform: translateY(-5px);
      }
      .product-image-container {
         position: relative;
         width: 100%;
         padding-top: 75%; /* 4:3 Aspect Ratio */
         overflow: hidden;
      }
      .product-image {
         position: absolute;
         top: 0;
         left: 0;
         width: 100%;
         height: 100%;
         object-fit: contain; /* Changed from cover to contain */
         background-color: #f8f9fa; /* Light background for images */
         padding: 10px; /* Add some padding around images */
      }
      .product-info {
         padding: 20px;
         flex-grow: 1;
         display: flex;
         flex-direction: column;
      }
      .product-name {
         font-size: 18px;
         font-weight: 600;
         color: #2d3436;
         margin-bottom: 10px;
         min-height: 54px; /* Allow for 2 lines of text */
         display: -webkit-box;
         -webkit-line-clamp: 2;
         -webkit-box-orient: vertical;
         overflow: hidden;
         text-overflow: ellipsis;
      }
      .product-price {
         color: #ff6b6b;
         font-size: 20px;
         font-weight: 600;
         margin-bottom: 15px;
      }
      .add-to-cart-btn {
         background: #ff6b6b;
         color: white;
         border: none;
         padding: 12px 20px;
         border-radius: 25px;
         width: 100%;
         font-weight: 500;
         transition: all 0.3s ease;
         cursor: pointer;
         margin-top: auto; /* Push button to bottom */
      }
      .add-to-cart-btn:hover {
         background: #ff5252;
         transform: translateY(-2px);
      }
      .add-to-cart-btn:disabled {
         background: #ffa5a5;
         cursor: not-allowed;
         transform: none;
      }
      .no-results {
         text-align: center;
         padding: 40px;
         color: #666;
         font-size: 18px;
         grid-column: 1 / -1;
      }
      .message {
         background-color: #d4edda;
         color: #155724;
         padding: 15px;
         margin: 20px 0;
         border-radius: 5px;
         border: 1px solid #c3e6cb;
         position: relative;
         animation: slideIn 0.3s ease-out;
      }
      .message.error {
         background-color: #f8d7da;
         color: #721c24;
         border-color: #f5c6cb;
      }
      .message i {
         position: absolute;
         right: 15px;
         top: 50%;
         transform: translateY(-50%);
         cursor: pointer;
         color: inherit;
      }
      @keyframes slideIn {
         from {
            transform: translateY(-20px);
            opacity: 0;
         }
         to {
            transform: translateY(0);
            opacity: 1;
         }
      }
      .floating-cart {
         position: fixed;
         bottom: 30px;
         right: 30px;
         background: linear-gradient(45deg, #FF416C, #FF4B2B);
         color: white;
         width: 60px;
         height: 60px;
         border-radius: 50%;
         display: flex;
         align-items: center;
         justify-content: center;
         text-decoration: none;
         box-shadow: 0 5px 15px rgba(255, 65, 108, 0.3);
         transition: all 0.3s;
         z-index: 1000;
      }
      .floating-cart:hover {
         transform: translateY(-5px);
         box-shadow: 0 8px 25px rgba(255, 65, 108, 0.4);
         color: white;
         text-decoration: none;
      }
      .floating-cart i {
         font-size: 24px;
      }
      .floating-cart .cart-count {
         position: absolute;
         top: -5px;
         right: -5px;
         background: white;
         color: #FF416C;
         border-radius: 50%;
         width: 24px;
         height: 24px;
         font-size: 14px;
         display: flex;
         align-items: center;
         justify-content: center;
         font-weight: bold;
      }
      @media (max-width: 768px) {
         .box-container {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
         }
         .floating-cart {
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
         }
         .floating-cart i {
            font-size: 20px;
         }
      }
   </style>
</head>
<body>
   <?php include 'navbar.php'; ?>

   <div class="container">
      <?php
      if(isset($message)){
         foreach($message as $msg){
            echo '<div class="message'.(strpos($msg, 'already') !== false ? ' error' : '').'"><span>'.$msg.'</span> <i class="fas fa-times" onclick="this.parentElement.style.display = `none`;"></i></div>';
         }
      }
      ?>

      <div class="search-container">
         <input type="text" id="searchInput" class="search-input" placeholder="Search for food items...">
         <i class="fas fa-search search-icon"></i>
      </div>

      <div class="products" id="productsContainer">
         <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `product`") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
         ?>
         <div class="product-card">
            <div class="product-image-container">
               <img src="admin/images/<?= htmlspecialchars($fetch_products['image']) ?>" alt="" class="product-image">
            </div>
            <div class="product-info">
               <h3 class="product-name"><?= htmlspecialchars($fetch_products['name']) ?></h3>
               <div class="product-price">₹<?= htmlspecialchars($fetch_products['price']) ?></div>
               <form action="" method="post">
                  <input type="hidden" name="product_name" value="<?= htmlspecialchars($fetch_products['name']) ?>">
                  <input type="hidden" name="product_price" value="<?= htmlspecialchars($fetch_products['price']) ?>">
                  <input type="hidden" name="product_image" value="<?= htmlspecialchars($fetch_products['image']) ?>">
                  <input type="submit" class="add-to-cart-btn" value="add to cart" name="add_to_cart">
               </form>
            </div>
         </div>
         <?php
            }
         } else {
            echo '<div class="no-results">No products available</div>';
         }
         ?>
      </div>
   </div>

   <?php if ($cart_count > 0): ?>
   <a href="cart.php" class="floating-cart">
      <i class="fas fa-shopping-cart"></i>
      <span class="cart-count"><?php echo $cart_count; ?></span>
   </a>
   <?php endif; ?>

   <script>
    // Refresh cart count without page reload
    function updateCartCount(count) {
        const navCartCount = document.querySelector('.cart-link .cart-count');
        const floatingCart = document.querySelector('.floating-cart');
        
        console.log('Updating cart count to:', count);
        
        // Update navbar cart count
        if (count > 0) {
            if (navCartCount) {
                navCartCount.textContent = count;
            } else {
                const span = document.createElement('span');
                span.className = 'cart-count';
                span.textContent = count;
                document.querySelector('.cart-link').appendChild(span);
            }
        } else if (navCartCount) {
            navCartCount.remove();
        }

        // Update floating cart
        if (count > 0) {
            if (!floatingCart) {
                const newFloatingCart = document.createElement('a');
                newFloatingCart.href = 'cart.php';
                newFloatingCart.className = 'floating-cart';
                newFloatingCart.innerHTML = `
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count">${count}</span>
                `;
                document.body.appendChild(newFloatingCart);
            } else {
                floatingCart.querySelector('.cart-count').textContent = count;
            }
        } else if (floatingCart) {
            floatingCart.remove();
        }
    }

    // Show message function
    function showMessage(text, isError = false) {
        console.log('Showing message:', text, 'isError:', isError);
        
        // Remove any existing messages first
        const existingMessages = document.querySelectorAll('.message');
        existingMessages.forEach(msg => msg.remove());

        // Create and add new message
        const message = document.createElement('div');
        message.className = 'message' + (isError ? ' error' : '');
        message.innerHTML = `<span>${text}</span><i class="fas fa-times" onclick="this.parentElement.remove()"></i>`;
        const container = document.querySelector('.container');
        container.insertBefore(message, container.firstChild);

        // Auto-remove message after 3 seconds
        setTimeout(() => message.remove(), 3000);
    }

    async function searchProducts(query) {
        try {
            console.log('Searching for:', query);
            const response = await fetch(`search_products.php?query=${encodeURIComponent(query)}`);
            
            // Log response status and headers
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            const contentType = response.headers.get('content-type');
            console.log('Content type:', contentType);
            
            let data;
            if (contentType && contentType.includes('application/json')) {
                data = await response.json();
                console.log('Response data:', data);
            } else {
                const text = await response.text();
                console.error('Unexpected response type:', text);
                throw new Error('Server returned non-JSON response');
            }
            
            if (!data.success) {
                throw new Error(data.message || 'Unknown error occurred');
            }
            
            const productsContainer = document.getElementById('productsContainer');
            productsContainer.innerHTML = '';
            
            if (!data.products || data.products.length === 0) {
                productsContainer.innerHTML = '<div class="no-results">No products found</div>';
                return;
            }
            
            data.products.forEach(product => {
                const productCard = `
                    <div class="product-card">
                        <div class="product-image-container">
                            <img src="admin/images/${product.image}" alt="" class="product-image">
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">${product.name}</h3>
                            <div class="product-price">₹${product.price}</div>
                            <form action="" method="post">
                                <input type="hidden" name="product_name" value="${product.name}">
                                <input type="hidden" name="product_price" value="${product.price}">
                                <input type="hidden" name="product_image" value="${product.image}">
                                <input type="submit" class="add-to-cart-btn" value="add to cart" name="add_to_cart">
                            </form>
                        </div>
                    </div>
                `;
                productsContainer.insertAdjacentHTML('beforeend', productCard);
            });
            
            // Reattach event listeners to new forms
            attachFormListeners();
            
        } catch (error) {
            console.error('Search error:', error);
            showMessage(error.message || 'Error searching products', true);
        }
    }

    // Function to attach event listeners to forms
    function attachFormListeners() {
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', handleFormSubmit);
        });
    }

    // Function to handle form submission
    async function handleFormSubmit(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('input[type="submit"]');
        submitBtn.disabled = true;
        
        try {
            const response = await fetch('add_to_cart.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            
            if (result.success) {
                updateCartCount(result.count);
                showMessage(result.message);
            } else {
                showMessage(result.message, true);
            }
        } catch (error) {
            console.error('Error:', error);
            showMessage('Error adding product to cart', true);
        } finally {
            submitBtn.disabled = false;
        }
    }

    // Initial attachment of form listeners
    attachFormListeners();

    // Search input handler with debouncing
    const searchInput = document.getElementById('searchInput');
    let debounceTimer;

    searchInput.addEventListener('input', (e) => {
        clearTimeout(debounceTimer);
        const query = e.target.value.trim();
        
        if (query.length === 0) {
            window.location.reload();
            return;
        }
        
        debounceTimer = setTimeout(() => {
            searchProducts(query);
        }, 300);
    });
   </script>
</body>
</html>