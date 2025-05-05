<!DOCTYPE HTML>
<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../Model/Product.php';
require_once __DIR__ . '/../../Model/Cart.php';

// Initialize session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Initialize Product class
$productModel = new Product();
$products = $productModel->getAllProducts();

// Check if user is logged in
$isLoggedIn = isset($_SESSION['id_user']) && !empty($_SESSION['id_user']);
$username = $isLoggedIn ? $_SESSION['username'] : '';

// If not logged in, redirect to login page
if (!$isLoggedIn) {
    header("Location: /web_php_mvc/View/auth/login.php");
    exit;
}

// If $cartItems is not set (direct access to page), get cart items
if (!isset($cartItems)) {
    error_log("Direct access to cart.php detected - loading cart items manually");
    $cartModel = new Cart();
    $userId = $_SESSION['id_user'];
    $cartItems = $cartModel->getCart($userId);
    
    // Calculate cart totals
    $subTotal = 0;
    $discount = 0;
    $total = 0;
    
    foreach ($cartItems as $item) {
        $subTotal += $item['total_price'];
    }
    
    $total = $subTotal - $discount;
    
    error_log("Loaded " . count($cartItems) . " cart items for user ID: " . $userId);
}
?>
<html>
	<head>
	<title>Shopping Cart - Shoes Shop</title>
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
        .product-cart {
            margin-bottom: 20px;
            padding: 15px 10px;
            border-bottom: 1px solid #e6e6e6;
            transition: background-color 0.3s ease;
        }
        .product-cart:hover {
            background-color: #f9f9f9;
        }
        .product-name {
            background-color: #f5f5f5;
            padding: 15px 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .closed {
            font-size: 18px;
            color: #777;
            transition: color 0.3s ease;
        }
        .closed:hover {
            color: #f45d01;
        }
        .item-quantity {
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .total {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
        }
        .total .sub p {
            font-size: 15px;
            margin-bottom: 10px;
        }
        .grand-total p {
            font-size: 18px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e6e6e6;
        }
    </style>
	</head>
	<body>
		
	<div class="colorlib-loader"></div>

	<div id="page">
		<?php include 'header.php'; ?>

		<div class="breadcrumbs">
			<div class="container">
				<div class="row">
					<div class="col">
						<p class="bread"><span><a href="/web_php_mvc/">Home</a></span> / <span>Shopping Cart</span></p>
					</div>
				</div>
			</div>
		</div>

		<div class="colorlib-product">
			<div class="container">
				<div class="row row-pb-lg">
					<div class="col-md-10 offset-md-1">
						<div class="process-wrap">
							<div class="process text-center active">
								<p><span>01</span></p>
								<h3>Shopping Cart</h3>
							</div>
							<div class="process text-center">
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
			<div class="row row-pb-lg">
				<div class="col-md-12">
					<div class="product-name d-flex">
						<div class="one-forth text-left px-4">
							<span style="font-size: 16px; font-weight: 600;">Product Details</span>
						</div>
						<div class="one-eight text-center">
							<span style="font-size: 16px; font-weight: 600;">Price</span>
						</div>
						<div class="one-eight text-center">
							<span style="font-size: 16px; font-weight: 600;">Quantity</span>
						</div>
						<div class="one-eight text-center">
							<span style="font-size: 16px; font-weight: 600;">Total</span>
						</div>
						<div class="one-eight text-center px-4">
							<span style="font-size: 16px; font-weight: 600;">Remove</span>
						</div>
					</div>
					
                    <?php if (isset($cartItems) && !empty($cartItems)): ?>
                        <?php foreach ($cartItems as $item): ?>
                            <div class="product-cart d-flex cart-item" data-variant-id="<?php echo $item['id_variant']; ?>">
                                <div class="one-forth">
                                    <div class="product-img" style="background-image: url('/web_php_mvc/public/images/<?php echo htmlspecialchars($item['imageUrl']); ?>');">
                                    </div>
                                    <div class="display-tc" style="padding-left: 15px; padding-top: 10px;">
                                        <h3 style="font-size: 18px; margin-bottom: 8px; text-align: left; font-weight: 600; padding-left: 0;"><?php echo htmlspecialchars($item['name_product']); ?></h3>
                                        <p style="font-size: 14px; margin-bottom: 0; text-align: left; color: #666; padding-left: 0;">
                                            <span style="display: inline-block; min-width: 45px;">Size:</span> <span style="font-weight: 500;"><?php echo htmlspecialchars($item['size_value'] ?? 'N/A'); ?></span>, 
                                            <span style="display: inline-block; min-width: 50px; margin-left: 10px;">Color:</span> <span style="font-weight: 500;"><?php echo htmlspecialchars($item['color_name'] ?? 'N/A'); ?></span>
                                        </p>
                                    </div>
                                </div>
                                <div class="one-eight text-center">
                                    <div class="display-tc">
                                        <span class="price" style="font-size: 16px; font-weight: 500;"><?php echo number_format($item['price'], 0, ',', '.'); ?> đ</span>
                                    </div>
                                </div>
                                <div class="one-eight text-center">
                                    <div class="display-tc">
                                        <input type="number" name="quantity" class="form-control input-number text-center item-quantity" value="<?php echo $item['quantity']; ?>" min="1" max="100" style="font-size: 16px; padding: 6px 4px;">
                                    </div>
                                </div>
                                <div class="one-eight text-center">
                                    <div class="display-tc">
                                        <span class="price item-total" style="font-size: 16px; font-weight: 600; color: rgb(0, 220, 180);"><?php echo number_format($item['total_price'], 0, ',', '.'); ?> đ</span>
                                    </div>
                                </div>
                                <div class="one-eight text-center">
                                    <div class="display-tc">
                                        <a href="#" class="closed remove-item"></a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="product-cart d-flex">
                            <div class="col-md-12 text-center">
                                <p>Your cart is empty</p>
                                <?php if (isset($_SESSION['id_user'])): ?>
                                    <p>User ID: <?php echo $_SESSION['id_user']; ?></p>
                                    <p>
                                        <?php
                                        // Check if there are any cart items in the database
                                        $cartModel = new Cart();
                                        $rawCount = $cartModel->getCartCount($_SESSION['id_user']);
                                        echo "Raw cart count from database: " . $rawCount;
                                        ?>
                                    </p>
                                <?php endif; ?>
                                <p><a href="/web_php_mvc/" class="btn btn-primary">Continue Shopping</a></p>
                            </div>
                        </div>
                    <?php endif; ?>
				</div>
			</div>
			<div class="row row-pb-lg">
				<div class="col-md-12">
					<div class="total-wrap">
						<div class="row">
							<div class="col-sm-8">
								<?php if (isset($cartItems) && !empty($cartItems)): ?>
                                    <form action="#">
                                        <div class="row form-group">
                                            <div class="col-sm-9">
                                                <input type="text" name="coupon" class="form-control input-number" placeholder="Your Coupon Number...">
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="button" value="Apply Coupon" class="btn btn-primary" id="apply-coupon">
                                            </div>
                                        </div>
                                    </form>
                                <?php endif; ?>
							</div>
							<div class="col-sm-4 text-center">
								<div class="total">
									<div class="sub">
										<p><span style="font-weight: 500;">Subtotal:</span> <span id="subtotal" style="font-weight: 600;"><?php echo number_format($subTotal ?? 0, 0, ',', '.'); ?> đ</span></p>
										<p><span style="font-weight: 500;">Delivery:</span> <span style="font-weight: 600;">0 đ</span></p>
										<p><span style="font-weight: 500;">Discount:</span> <span id="discount" style="font-weight: 600;"><?php echo number_format($discount ?? 0, 0, ',', '.'); ?> đ</span></p>
									</div>
									<div class="grand-total">
										<p><span style="font-weight: 600;">Total:</span> <span id="total" style="font-weight: 700; color:rgb(0, 220, 180); font-size: 20px;"><?php echo number_format($total ?? 0, 0, ',', '.'); ?> đ</span></p>
									</div>
                                    <?php if (isset($cartItems) && !empty($cartItems)): ?>
                                        <div class="mt-3">
                                        <a href="/web_php_mvc/View/user/payment.php" class="btn btn-primary btn-block" style="font-size: 16px; padding: 12px;">Proceed to Checkout</a>
                                        </div>
                                    <?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php include 'footer.php'; ?>

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
	<!-- Magnific Popup plugin -->
	<script src="/web_php_mvc/public/js/jquery.magnific-popup.min.js"></script>
	<!-- Your custom options -->
	<script src="/web_php_mvc/public/js/magnific-popup-options.js"></script>
	<!-- Date Picker -->
	<script src="/web_php_mvc/public/js/bootstrap-datepicker.js"></script>
	<!-- Stellar Parallax -->
	<script src="/web_php_mvc/public/js/jquery.stellar.min.js"></script>
	<!-- Main -->
	<script src="/web_php_mvc/public/js/main.js"></script>
    
    <script>
        $(document).ready(function(){
            // Update quantity
            $('.item-quantity').on('change', function() {
                var $item = $(this).closest('.cart-item');
                var variantId = $item.data('variant-id');
                var quantity = parseInt($(this).val());
                
                if (quantity < 1) {
                    $(this).val(1);
                    quantity = 1;
                }
                
                updateCartItem(variantId, quantity);
            });
            
            // Remove item
            $('.remove-item').on('click', function(e) {
                e.preventDefault();
                var $item = $(this).closest('.cart-item');
                var variantId = $item.data('variant-id');
                
                if (confirm('Are you sure you want to remove this item from your cart?')) {
                    removeCartItem(variantId);
                }
            });
            
            // Apply coupon (placeholder functionality)
            $('#apply-coupon').on('click', function() {
                alert('Coupon functionality will be implemented in the future.');
            });
            
            // Function to update cart item
            function updateCartItem(variantId, quantity) {
                $.ajax({
                    url: '/web_php_mvc/process_cart.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'update',
                        variant_id: variantId,
                        quantity: quantity
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            // Update the item total price
                            var $item = $('.cart-item[data-variant-id="' + variantId + '"]');
                            $item.find('.item-total').text(formatPrice(response.item_total) + ' đ');
                            
                            // Update the subtotal and total
                            $('#subtotal').text(formatPrice(response.sub_total) + ' đ');
                            $('#total').text(formatPrice(response.total) + ' đ');
                            
                            // Update cart count in header if needed
                            if (response.cart_count) {
                                $('.cart-item-count').text(response.cart_count);
                            }
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('An error occurred. Please try again later.');
                    }
                });
            }
            
            // Function to remove cart item
            function removeCartItem(variantId) {
                $.ajax({
                    url: '/web_php_mvc/process_cart.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'remove',
                        variant_id: variantId
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            // Remove the item from the DOM
                            $('.cart-item[data-variant-id="' + variantId + '"]').fadeOut(300, function() {
                                $(this).remove();
                                
                                // If cart is empty, refresh the page to show empty cart message
                                if (response.cart_count === 0) {
                                    location.reload();
                                }
                            });
                            
                            // Update the subtotal and total
                            $('#subtotal').text(formatPrice(response.sub_total) + ' đ');
                            $('#total').text(formatPrice(response.total) + ' đ');
                            
                            // Update cart count in header if needed
                            if (response.cart_count !== undefined) {
                                $('.cart-item-count').text(response.cart_count);
                            }
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('An error occurred. Please try again later.');
                    }
                });
            }
            
            // Helper function to format price
            function formatPrice(price) {
                return new Intl.NumberFormat('vi-VN').format(price);
            }
        });
    </script>
	</body>
</html>

