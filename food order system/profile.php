<?php
require_once 'auth.php';

// Check authentication and session timeout
checkAuth();
if (checkSessionTimeout()) {
    header('Location: login.php?msg=session_expired');
    exit();
}

$connect = mysqli_connect("localhost", "root", "", "food") or die("Connection failed");

// Get user information
$userEmail = getUserEmail();

// Fetch user details using email
$stmt = $connect->prepare("SELECT * FROM users WHERE Email = ?");
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header('Location: login.php');
    exit();
}

// Check if corder table exists and fetch orders
$orders = [];
$hasOrderTable = false;
$checkTable = $connect->query("SHOW TABLES LIKE 'corder'");
if ($checkTable->num_rows > 0) {
    try {
        // Fetch order history
        $orderStmt = $connect->prepare("
            SELECT id as order_id, 
                   CAST(total_products AS CHAR) as total_products,
                   total_price as total_amount,
                   ord_date as order_date,
                   method as payment_method,
                   CONCAT(flat, ', ', street, ', ', city, ' - ', pin_code) as delivery_address
            FROM corder 
            WHERE email = ?
            ORDER BY ord_date DESC
            LIMIT 10
        ");
        $orderStmt->bind_param("s", $userEmail);
        $orderStmt->execute();
        $orderResult = $orderStmt->get_result();
        while ($row = $orderResult->fetch_assoc()) {
            // Clean and format the products string
            $products = preg_replace('/[^a-zA-Z0-9\s,()]/', '', $row['total_products']);
            $row['items'] = $products;
            $orders[] = $row;
        }
        $orderStmt->close();
        $hasOrderTable = true;
    } catch (Exception $e) {
        error_log("Error fetching order history: " . $e->getMessage());
    }
}

// Handle profile updates
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $dietary_preferences = $_POST['dietary_preferences'] ?? '';
    $delivery_instructions = $_POST['delivery_instructions'] ?? '';
    
    // Validate phone number
    if (!empty($phone) && !preg_match("/^[0-9]{10}$/", $phone)) {
        $message = "Please enter a valid 10-digit phone number";
        $messageType = "danger";
    } else {
        try {
            // First, try updating with all fields
            $updateStmt = $connect->prepare("
                UPDATE users 
                SET Name=?, Phone=?, Address=?, 
                    dietary_preferences=?, delivery_instructions=? 
                WHERE Email=?
            ");
            $updateStmt->bind_param("ssssss", $name, $phone, $address, $dietary_preferences, $delivery_instructions, $userEmail);
            $updateStmt->execute();
        } catch (mysqli_sql_exception $e) {
            // If the new columns don't exist, update only the basic fields
            if (strpos($e->getMessage(), "Unknown column") !== false) {
                $updateStmt = $connect->prepare("UPDATE users SET Name=?, Phone=?, Address=? WHERE Email=?");
                $updateStmt->bind_param("ssss", $name, $phone, $address, $userEmail);
                $updateStmt->execute();
            } else {
                throw $e; // Re-throw if it's a different error
            }
        }
        
        $message = "Profile updated successfully!";
        $messageType = "success";
        
        // Refresh user data
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        $updateStmt->close();
    }
}

$stmt->close();
$connect->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - FoodHub</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
            padding-top: 80px;
        }
        .profile-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 30px;
        }
        .profile-header {
            margin-bottom: 30px;
            text-align: center;
            position: relative;
        }
        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #2980b9;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            margin: 0 auto 20px;
        }
        .form-group label {
            font-weight: 600;
            color: #2c3e50;
        }
        .btn-primary {
            background-color: #2980b9;
            border-color: #2980b9;
            padding: 10px 30px;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background-color: #2471a3;
            border-color: #2471a3;
            transform: translateY(-2px);
        }
        .form-control:focus {
            border-color: #2980b9;
            box-shadow: 0 0 0 0.2rem rgba(41, 128, 185, 0.25);
        }
        .order-history {
            margin-top: 40px;
        }
        .order-card {
            border-left: 4px solid #2980b9;
            margin-bottom: 15px;
            padding: 15px;
            background: #f8f9fa;
            transition: all 0.3s;
        }
        .order-card:hover {
            transform: translateX(5px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .nav-tabs {
            border-bottom: 2px solid #dee2e6;
            margin-bottom: 30px;
        }
        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            padding: 10px 20px;
            font-weight: 500;
        }
        .nav-tabs .nav-link.active {
            color: #2980b9;
            border-bottom: 2px solid #2980b9;
        }
        .section-title {
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="profile-card">
                    <div class="profile-header">
                        <div class="profile-avatar">
                            <?php echo strtoupper(substr($user['Name'] ?? 'U', 0, 1)); ?>
                        </div>
                        <h2>Profile Settings</h2>
                        <p class="text-muted">Manage your account information</p>
                    </div>

                    <?php if ($message): ?>
                        <div class="alert alert-<?php echo $messageType; ?>" role="alert">
                            <?php echo htmlspecialchars($message); ?>
                        </div>
                    <?php endif; ?>

                    <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="personal-tab" data-toggle="tab" href="#personal" role="tab">Personal Info</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="preferences-tab" data-toggle="tab" href="#preferences" role="tab">Preferences</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="orders-tab" data-toggle="tab" href="#orders" role="tab">Order History</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="profileTabsContent">
                        <div class="tab-pane fade show active" id="personal" role="tabpanel">
                            <form method="POST" action="" id="profileForm">
                                <div class="form-group">
                                    <label><i class="fas fa-envelope mr-2"></i>Email</label>
                                    <input type="email" class="form-control" value="<?php echo htmlspecialchars($userEmail); ?>" readonly>
                                    <small class="text-muted">Email cannot be changed</small>
                                </div>

                                <div class="form-group">
                                    <label><i class="fas fa-user mr-2"></i>Name</label>
                                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['Name'] ?? ''); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label><i class="fas fa-phone mr-2"></i>Phone</label>
                                    <input type="tel" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['Phone'] ?? ''); ?>" pattern="[0-9]{10}" title="Please enter a valid 10-digit phone number">
                                    <small class="text-muted">Enter 10-digit phone number</small>
                                </div>

                                <div class="form-group">
                                    <label><i class="fas fa-map-marker-alt mr-2"></i>Address</label>
                                    <textarea name="address" class="form-control" rows="3"><?php echo htmlspecialchars($user['Address'] ?? ''); ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label><i class="fas fa-truck mr-2"></i>Delivery Instructions</label>
                                    <textarea name="delivery_instructions" class="form-control" rows="2" placeholder="E.g., Ring doorbell, leave at front door, etc."><?php echo htmlspecialchars($user['delivery_instructions'] ?? ''); ?></textarea>
                                    <small class="text-muted">This feature will be available soon</small>
                                </div>

                                <div class="form-group">
                                    <label><i class="fas fa-utensils mr-2"></i>Dietary Preferences</label>
                                    <textarea name="dietary_preferences" class="form-control" rows="2" placeholder="E.g., Vegetarian, Allergies, etc."><?php echo htmlspecialchars($user['dietary_preferences'] ?? ''); ?></textarea>
                                    <small class="text-muted">This feature will be available soon</small>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-2"></i>Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="preferences" role="tabpanel">
                            <h4 class="section-title">Your Preferences</h4>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i>
                                Your dietary preferences and delivery instructions help us serve you better!
                            </div>
                            <!-- Preferences content is managed in the main form -->
                        </div>

                        <div class="tab-pane fade" id="orders" role="tabpanel">
                            <h4 class="section-title">Recent Orders</h4>
                            <?php if (!$hasOrderTable): ?>
                                <div class="text-center text-muted py-5">
                                    <i class="fas fa-tools fa-3x mb-3"></i>
                                    <p>Order history feature is coming soon!</p>
                                    <a href="products.php" class="btn btn-primary">View Menu</a>
                                </div>
                            <?php elseif (empty($orders)): ?>
                                <div class="text-center text-muted py-5">
                                    <i class="fas fa-shopping-bag fa-3x mb-3"></i>
                                    <p>No orders yet. Start ordering delicious food!</p>
                                    <a href="products.php" class="btn btn-primary">View Menu</a>
                                </div>
                            <?php else: ?>
                                <?php foreach ($orders as $order): ?>
                                    <div class="order-card">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <strong class="text-primary">Order #<?php echo htmlspecialchars($order['order_id']); ?></strong>
                                                <br>
                                                <small class="text-muted">
                                                    <?php echo date('M d, Y H:i', strtotime($order['order_date'])); ?>
                                                </small>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-0"><?php echo htmlspecialchars($order['items']); ?></p>
                                                <small class="text-muted">
                                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                                    <?php echo htmlspecialchars($order['delivery_address']); ?>
                                                </small>
                                            </div>
                                            <div class="col-md-3 text-right">
                                                <strong>₹<?php echo number_format($order['total_amount'], 2); ?></strong>
                                                <br>
                                                <span class="badge badge-info">
                                                    <i class="fas fa-credit-card mr-1"></i>
                                                    <?php echo htmlspecialchars($order['payment_method']); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Client-side validation
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            const phone = document.querySelector('input[name="phone"]').value.trim();
            if (phone && !/^[0-9]{10}$/.test(phone)) {
                e.preventDefault();
                alert('Please enter a valid 10-digit phone number');
            }
        });

        // Preserve active tab after form submission
        $(document).ready(function() {
            let activeTab = localStorage.getItem('activeProfileTab');
            if (activeTab) {
                $('#profileTabs a[href="' + activeTab + '"]').tab('show');
            }

            $('#profileTabs a').on('click', function (e) {
                localStorage.setItem('activeProfileTab', $(this).attr('href'));
            });
        });
    </script>
</body>
</html>