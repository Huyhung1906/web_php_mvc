<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../Model/Order.php';
require_once __DIR__ . '/../../Model/user.php';

// Initialize session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Enable debugging for this session
$_SESSION['debug'] = true;

// Check if user is logged in
if (!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])) {
    header("Location: /web_php_mvc/View/auth/login.php");
    exit;
}

$userId = $_SESSION['id_user'];
$username = $_SESSION['username'];

// Get all orders for this user
$orderModel = new Order();

// Debug: Print the actual user ID
echo "<!-- DEBUG: User ID: $userId -->";

$orders = [];
try {
    $orders = $orderModel->getUserOrders($userId);
    
    // Debug info
    echo "<!-- DEBUG: Retrieved " . count($orders) . " orders -->";
    if (!empty($orders)) {
        echo "<!-- First order ID: " . $orders[0]['id_order'] . " -->";
    }
} catch (Exception $e) {
    // Log the error
    error_log("Error retrieving orders: " . $e->getMessage());
    echo "<!-- DEBUG ERROR: " . $e->getMessage() . " -->";
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>My Invoices - Footwear</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Rokkitt:100,300,400,700" rel="stylesheet">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="/web_php_mvc/public/css/animate.css">
    <!-- Icomoon Icon Fonts-->
    <link rel="stylesheet" href="/web_php_mvc/public/css/icomoon.css">
    <!-- Ion Icon Fonts-->
    <link rel="stylesheet" href="/web_php_mvc/public/css/ionicons.min.css">
    <!-- Bootstrap  -->
    <link rel="stylesheet" href="/web_php_mvc/public/css/bootstrap.min.css">
    <!-- Magnific Popup -->
    <link rel="stylesheet" href="/web_php_mvc/public/css/magnific-popup.css">
    <!-- Flexslider  -->
    <link rel="stylesheet" href="/web_php_mvc/public/css/flexslider.css">
    <!-- Owl Carousel -->
    <link rel="stylesheet" href="/web_php_mvc/public/css/owl.carousel.min.css">
    <link rel="stylesheet" href="/web_php_mvc/public/css/owl.theme.default.min.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="/web_php_mvc/public/css/bootstrap-datepicker.css">
    <!-- Theme style  -->
    <link rel="stylesheet" href="/web_php_mvc/public/css/style.css">
    <style>
        .invoice-header {
            background-color: #f5f5f5;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .order-card {
            border: 1px solid #e6e6e6;
            border-radius: 8px;
            margin-bottom: 25px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .order-header {
            background-color: #f9f9f9;
            padding: 15px;
            border-bottom: 1px solid #e6e6e6;
        }
        .order-items {
            padding: 15px;
        }
        .order-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .item-image {
            width: 70px;
            height: 70px;
            background-size: cover;
            background-position: center;
            border-radius: 5px;
            margin-right: 15px;
        }
        .order-footer {
            background-color: #f9f9f9;
            padding: 15px;
            border-top: 1px solid #e6e6e6;
            text-align: right;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        .no-orders {
            text-align: center;
            padding: 50px 0;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div id="page">
        <?php include 'header.php'; ?>

        <div class="breadcrumbs">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <p class="bread"><span><a href="/web_php_mvc/">Home</a></span> / <span>Invoices</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="colorlib-product">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="invoice-header">
                            <h2 class="mb-0">My Orders</h2>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <?php
                        // Debug information
                        if (empty($orders)) {
                            error_log("No orders found for user: $userId");
                        } else {
                            error_log("Found " . count($orders) . " orders for user: $userId");
                        }
                        ?>
                        
                        <?php if (!empty($orders)): ?>
                            <?php foreach ($orders as $order): ?>
                                <div class="order-card">
                                    <div class="order-header">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h5>Order #<?php echo htmlspecialchars($order['id_order']); ?></h5>
                                                <p class="mb-0"><small>Date: <?php echo date('F j, Y, g:i a', strtotime($order['created_at'])); ?></small></p>
                                            </div>
                                            <div class="col-md-4 text-right">
                                                <?php 
                                                $statusClass = '';
                                                switch(strtolower($order['status'])) {
                                                    case 'pending':
                                                        $statusClass = 'status-pending';
                                                        break;
                                                    case 'completed':
                                                        $statusClass = 'status-completed';
                                                        break;
                                                    case 'cancelled':
                                                        $statusClass = 'status-cancelled';
                                                        break;
                                                }
                                                ?>
                                                <span class="status-badge <?php echo $statusClass; ?>"><?php echo htmlspecialchars($order['status']); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="order-items">
                                        <h6>Items:</h6>
                                        <?php foreach ($order['items'] as $item): ?>
                                            <div class="order-item">
                                                <div class="item-image" style="background-image: url('/web_php_mvc/public/images/<?php echo htmlspecialchars($item['imageUrl']); ?>');"></div>
                                                <div style="flex-grow: 1;">
                                                    <h6 style="margin-bottom: 5px;"><?php echo htmlspecialchars($item['name_product']); ?></h6>
                                                    <p class="mb-0" style="font-size: 14px; color: #666;">
                                                        Size: <?php echo htmlspecialchars($item['size_value'] ?? 'N/A'); ?>, 
                                                        Color: <?php echo htmlspecialchars($item['color_name'] ?? 'N/A'); ?>
                                                    </p>
                                                </div>
                                                <div style="text-align: right; margin-left: 15px;">
                                                    <p class="mb-0" style="font-size: 14px;">
                                                        <span><?php echo number_format($item['price'], 0, ',', '.'); ?> đ</span> x 
                                                        <span><?php echo $item['quantity']; ?></span>
                                                    </p>
                                                    <p class="mb-0" style="font-weight: 600; color: #00c9a7;">
                                                        <?php echo number_format($item['total_price'], 0, ',', '.'); ?> đ
                                                    </p>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="order-footer">
                                        <p style="font-size: 14px;">Payment Method: <span style="font-weight: 600;"><?php echo htmlspecialchars($order['payment_method']); ?></span></p>
                                        <p style="font-size: 14px;">Shipping Address: <span style="font-weight: 600;"><?php echo htmlspecialchars($order['shipping_address']); ?></span></p>
                                        <p style="font-weight: 700; font-size: 18px; color: #00c9a7; margin-top: 10px;">
                                            Total: <?php echo number_format($order['total_amount'], 0, ',', '.'); ?> đ
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="no-orders">
                                <i class="icon-shopping-cart" style="font-size: 48px; display: block; margin-bottom: 20px;"></i>
                                <h4>You haven't placed any orders yet</h4>
                                <p>Browse our products and make your first purchase!</p>
                                <a href="/web_php_mvc/" class="btn btn-primary">Start Shopping</a>
                                
                                <?php if (isset($_SESSION['debug']) && $_SESSION['debug']): ?>
                                <div class="mt-4 p-3 bg-light">
                                    <h5>Debug Information:</h5>
                                    <p>User ID: <?php echo $userId; ?></p>
                                    <p>Order Model Method: getUserOrders</p>
                                </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php include 'footer.php'; ?>
    </div>

    <div class="gototop js-top">
        <a href="#" class="js-gotop"><i class="ion-ios-arrow-up"></i></a>
    </div>
	
    <!-- jQuery -->
    <script src="/web_php_mvc/public/js/jquery.min.js"></script>
    <!-- popper -->
    <script src="/web_php_mvc/public/js/popper.min.js"></script>
    <!-- bootstrap 4.1 -->
    <script src="/web_php_mvc/public/js/bootstrap.min.js"></script>
    <!-- jQuery easing -->
    <script src="/web_php_mvc/public/js/jquery.easing.1.3.js"></script>
    <!-- Waypoints -->
    <script src="/web_php_mvc/public/js/jquery.waypoints.min.js"></script>
    <!-- Flexslider -->
    <script src="/web_php_mvc/public/js/jquery.flexslider-min.js"></script>
    <!-- Owl carousel -->
    <script src="/web_php_mvc/public/js/owl.carousel.min.js"></script>
    <!-- Magnific Popup -->
    <script src="/web_php_mvc/public/js/jquery.magnific-popup.min.js"></script>
    <script src="/web_php_mvc/public/js/magnific-popup-options.js"></script>
    <!-- Date Picker -->
    <script src="/web_php_mvc/public/js/bootstrap-datepicker.js"></script>
    <!-- Stellar Parallax -->
    <script src="/web_php_mvc/public/js/jquery.stellar.min.js"></script>
    <!-- Main -->
    <script src="/web_php_mvc/public/js/main.js"></script>

</body>
</html> 