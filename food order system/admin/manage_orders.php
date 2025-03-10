<?php
session_start();
include_once "./config/dbconnect.php";

if(!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: admlogin.php");
    exit();
}

// Update order status
if(isset($_POST['update_status'])) {
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    mysqli_query($conn, "UPDATE `corder` SET status = '$status' WHERE id = '$order_id'") or die('query failed');
    $message[] = 'Order status has been updated!';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans&display=swap');

        * {
            list-style: none;
            text-decoration: none;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Open Sans', sans-serif;
        }

        body {
            background: #f5f6fa;
        }

        .wrapper .sidebar {
            background: rgb(41,128,185);
            position: fixed;
            top: 0;
            left: 0;
            width: 225px;
            height: 100%;
            padding: 20px 0;
            transition: all 0.5s ease;
        }

        .orders {
            width: calc(100% - 225px);
            margin-left: 225px;
            padding: 20px;
        }

        .title {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
        }

        .box-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .box:hover {
            transform: translateY(-5px);
        }

        .box p {
            margin: 8px 0;
            color: #2c3e50;
        }

        .box p span {
            color: #7f8c8d;
            margin-left: 5px;
        }

        .status-select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 10px 0;
            background: white;
            color: #2c3e50;
        }

        .update-btn {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background: rgb(41,128,185);
            color: white;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .update-btn:hover {
            background: rgb(52, 152, 219);
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: 600;
            margin-top: 10px;
        }

        .status-preparing { background: #fff3cd; color: #856404; }
        .status-ready { background: #d4edda; color: #155724; }
        .status-out_for_delivery { background: #cce5ff; color: #004085; }
        .status-delivered { background: #d1e7dd; color: #0f5132; }

        .empty {
            text-align: center;
            padding: 20px;
            color: #6c757d;
            font-size: 1.2em;
        }

        @media (max-width: 768px) {
            .orders {
                width: 100%;
                margin-left: 0;
                padding: 10px;
            }

            .box-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<!--Top menu -->
<div class="wrapper">
    <!--Top menu -->
    <div class="sidebar">
        <div class="profile">
            <img src="./img/logo.png" alt="profile_picture">
            <h3>Admin</h3>
        </div>
        <ul>
            <li>
                <a href="./index.php">
                    <span class="icon"><i class="fas fa-home"></i></span>
                    <span class="item">Home</span>
                </a>
            </li>
            <li>
                <a href="./admin.php">
                    <span class="icon"><i class="fas fa-desktop"></i></span>
                    <span class="item">Add Items</span>
                </a>
            </li>
            <li>
                <a href="./orderlist.php">
                    <span class="icon"><i class="fa fa-shopping-cart" style="font-size:24px"></i></span>
                    <span class="item">Orders</span>
                </a>
            </li>
            <li>
                <a href="./manage_orders.php" class="active">
                    <span class="icon"><i class="fas fa-tasks"></i></span>
                    <span class="item">Manage Orders</span>
                </a>
            </li>
            <li>
                <a href="./feedback.php">
                    <span class="icon"><i class="fas fa-comments"></i></span>
                    <span class="item">Feedbacks</span>
                </a>
            </li>
            <li>
                <a href="./customers.php">
                    <span class="icon"><i class="fas fa-users"></i></span>
                    <span class="item">Customers</span>
                </a>
            </li>
            <li>
                <a href="http://localhost/food order system/food order system/home1.php">
                    <span class="icon"><i class="fas fa-database"></i></span>
                    <span class="item">Go to Website</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="section">
        <div class="top_navbar">
            <div class="hamburger">
                <a href="#">
                    <i class="fas fa-bars"></i>
                </a>
            </div>
        </div>

        <section class="orders">
            <h1 class="title">Manage Orders</h1>

            <?php if(isset($message)): ?>
                <?php foreach($message as $msg): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $msg; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="box-container">
                <?php
                $select_orders = mysqli_query($conn, "SELECT * FROM `corder` ORDER BY order_time DESC") or die('query failed');
                if(mysqli_num_rows($select_orders) > 0){
                    while($fetch_orders = mysqli_fetch_assoc($select_orders)){
                ?>
                <div class="box">
                    <p>Order ID: <span>#<?php echo $fetch_orders['id']; ?></span></p>
                    <p>Placed on: <span><?php echo date('d M Y, h:i A', strtotime($fetch_orders['order_time'])); ?></span></p>
                    <p>Name: <span><?php echo $fetch_orders['name']; ?></span></p>
                    <p>Phone: <span><?php echo $fetch_orders['number']; ?></span></p>
                    <p>Email: <span><?php echo $fetch_orders['email']; ?></span></p>
                    <p>Address: <span><?php echo $fetch_orders['flat'].', '.$fetch_orders['street'].', '.$fetch_orders['city'].' - '.$fetch_orders['pin_code']; ?></span></p>
                    <p>Payment method: <span><?php echo $fetch_orders['method']; ?></span></p>
                    <p>Orders: <span><?php echo $fetch_orders['total_products']; ?></span></p>
                    <p>Total price: <span>₹<?php echo $fetch_orders['total_price']; ?></span></p>
                    
                    <div class="status-badge status-<?php echo $fetch_orders['status']; ?>">
                        Current Status: <?php echo ucfirst(str_replace('_', ' ', $fetch_orders['status'])); ?>
                    </div>
                    
                    <form action="" method="post">
                        <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                        <select name="status" class="status-select">
                            <option value="preparing" <?php if($fetch_orders['status'] == 'preparing') echo 'selected'; ?>>Preparing</option>
                            <option value="ready" <?php if($fetch_orders['status'] == 'ready') echo 'selected'; ?>>Ready</option>
                            <option value="out_for_delivery" <?php if($fetch_orders['status'] == 'out_for_delivery') echo 'selected'; ?>>Out for Delivery</option>
                            <option value="delivered" <?php if($fetch_orders['status'] == 'delivered') echo 'selected'; ?>>Delivered</option>
                        </select>
                        <input type="submit" name="update_status" value="Update Status" class="update-btn">
                    </form>
                </div>
                <?php
                    }
                } else {
                    echo '<p class="empty">No orders placed yet!</p>';
                }
                ?>
            </div>
        </section>
    </div>
</div>

</body>
</html>
