<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../Model/Product.php';
require_once __DIR__ . '/../../Model/Cart.php';
require_once __DIR__ . '/../../Model/user.php';

// Khởi tạo session nếu chưa có
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra đăng nhập
$isLoggedIn = isset($_SESSION['id_user']) && !empty($_SESSION['id_user']);
if (!$isLoggedIn) {
    header("Location: /web_php_mvc/View/auth/login.php");
    exit;
}

$userId = $_SESSION['id_user'];
$username = $_SESSION['username'];

// Lấy giỏ hàng
$cartModel = new Cart();
$cartItems = $cartModel->applyPromotions($userId);

// Check for items exceeding quantity limit
$exceededItems = [];
foreach ($cartItems as $item) {
    if ($item['quantity'] > 20) {
        $exceededItems[] = $item['name_product'];
    }
}

if (!empty($exceededItems)) {
    $_SESSION['quantity_error'] = "Không thể thanh toán vì các sản phẩm sau vượt quá giới hạn số lượng (tối đa 20 sản phẩm cho mỗi loại): " . implode(", ", $exceededItems);
    header("Location: /web_php_mvc/View/user/cart.php");
    exit;
}

// Tính tổng tiền
$subTotal = 0;
foreach ($cartItems as $item) {
    $subTotal += $item['total_price'];
}
$total = $subTotal;

// Lấy địa chỉ đã lưu
$userModel = new UserModel();
$userAddresses = $userModel->getUserAddresses($userId);
$user = $userModel->getUserById2($userId);

// Xử lý thông báo lỗi/thành công
$orderError = $_SESSION['order_error'] ?? '';
$orderSuccess = $_SESSION['order_success'] ?? '';
unset($_SESSION['order_error'], $_SESSION['order_success']);

if (isset($_GET['action']) && $_GET['action'] === 'placeOrder') {
    $controller = new CartController();
    $controller->placeOrder();
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Checkout - Payment</title>
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
        .order-table th, .order-table td {
            vertical-align: middle !important;
            text-align: center;
        }
        .order-table .product-info {
            text-align: left;
            display: flex;
            align-items: center;
        }
        .order-table .product-img {
            width: 80px;
            height: 80px;
            background-size: cover;
            background-position: center;
            border-radius: 8px;
            margin-right: 15px;
        }
        .order-summary {
            font-size: 18px;
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 20px;
            text-align: right;
        }
        .btn-pay {
            width: 100%;
            font-size: 18px;
            padding: 14px;
            margin-top: 25px;
            background: #222;
            color: #fff;
            border-radius: 30px;
            border: none;
            transition: background 0.2s;
        }
        .btn-pay:hover {
            background: #00c9a7;
            color: #fff;
        }
        .form-section {
            background: #fafafa;
            border-radius: 10px;
            padding: 25px 20px;
            margin-bottom: 30px;
        }
        .form-section h4 {
            margin-bottom: 18px;
        }
    </style>
</head>
<body>
<div class="colorlib-loader"></div>
<div id="page">
    <?php include 'header.php'; ?>

    <!-- breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col">
                    <p class="bread"><span><a href="/web_php_mvc/">Home</a></span> / <span>Checkout</span></p>
                </div>
            </div>
        </div>
    </div>

    <div class="colorlib-product">
        <div class="container">
            <div class="row row-pb-lg">
                <div class="col-md-10 offset-md-1">
                    <div class="process-wrap">
                        <div class="process text-center">
                            <p><span>01</span></p>
                            <h3>Shopping Cart</h3>
                        </div>
                        <div class="process text-center active">
                            <p><span>02</span></p>
                            <h3>Checkout</h3>
                        </div>
                        <div class="process text-center">
                            <p><span>03</span></p>
                            <h3>Order Complete</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container" style="margin-top: 40px; margin-bottom: 40px;">
                <div class="row">
                    <div class="col-md-6">
                        <form action="/web_php_mvc/Controller/CartController.php?action=placeOrder" method="post">
                            <div class="form-section">
                                <h4>Shipping Address</h4>
                                <?php if (!empty($userAddresses)): ?>
                                    <div class="form-group">
                                        <label>Choose saved address:</label>
                                        <select name="address" class="form-control" id="address-select">
                                            <option value="">-- Select address --</option>
                                            <?php foreach ($userAddresses as $address): ?>
                                                <option value="<?php echo htmlspecialchars($address['province'] . ', ' . $address['district'] . ', ' . $address['ward'] . ', ' . $address['street']); ?>">
                                                    <?php echo htmlspecialchars($address['province'] . ', ' . $address['district'] . ', ' . $address['ward'] . ', ' . $address['street']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                <?php endif; ?>
                                <p><strong>Recipient Name:</strong> <?php echo htmlspecialchars($user['fullname']); ?></p>
                                <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
                                <input type="hidden" name="customer_name" value="<?php echo htmlspecialchars($user['fullname']); ?>">
                                <input type="hidden" name="customer_phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                            </div>
                            <div class="form-section">
                                <h4>Payment Method</h4>
                                <div class="form-group">
                                    <input type="hidden" name="payment_method" value="Cash">
                                    <p style="margin: 0; padding: 8px 0 0 2px; font-size: 16px;">Cash</p>
                                </div>
                            </div>
                            <button type="submit" class="btn-pay">Pay now</button>
                            <?php if ($orderError): ?>
                                <div class="alert alert-danger mt-3"><?php echo htmlspecialchars($orderError); ?></div>
                            <?php endif; ?>
                            <?php if ($orderSuccess): ?>
                                <div class="alert alert-success mt-3"><?php echo htmlspecialchars($orderSuccess); ?></div>
                            <?php endif; ?>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <h3 style="margin-bottom: 25px;">Your Order</h3>
                        <table class="table order-table">
                            <thead>
                                <tr style="background: #f3f3f3;">
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (!empty($cartItems)): ?>
                                <?php foreach ($cartItems as $item): ?>
                                    <tr>
                                        <td class="product-info">
                                            <div class="product-img" style="background-image: url('/web_php_mvc/public/images/<?php echo htmlspecialchars($item['imageUrl']); ?>');"></div>
                                            <div>
                                                <strong><?php echo htmlspecialchars($item['name_product']); ?></strong><br>
                                                <span style="font-size: 13px;">Size: <?php echo htmlspecialchars($item['size_value'] ?? 'N/A'); ?>, Color: <?php echo htmlspecialchars($item['color_name'] ?? 'N/A'); ?></span>
                                            </div>
                                        </td>
                                        <td><?php echo number_format($item['price'], 0, ',', '.'); ?> đ</td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td><?php echo number_format($item['total_price'], 0, ',', '.'); ?> đ</td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4">Your cart is empty.</td></tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                        <div class="order-summary">
                            <strong>Total:</strong> <span style="color: #00c9a7; font-size: 22px;"><?php echo number_format($total, 0, ',', '.'); ?> đ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
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
<script>
    // Address logic
    document.querySelector('input[name="address_new"]').addEventListener('input', function() {
        if (this.value.trim() !== '') {
            document.getElementById('address-select')?.selectedIndex = 0;
        }
    });
    document.getElementById('address-select')?.addEventListener('change', function() {
        if (this.value !== '') {
            document.querySelector('input[name="address_new"]').value = '';
        }
    });
    $(window).on('load', function() {
        $('.colorlib-loader').fadeOut('slow');
    });
</script>
</body>
</html>
