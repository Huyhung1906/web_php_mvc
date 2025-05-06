<?php
// Check if product exists before displaying the page
if (!isset($product) || empty($product)) {
    header("Location: /web_php_mvc-master/");
    exit;
}
?>
<!DOCTYPE HTML>
<html>
	<head>
	<title><?php echo htmlspecialchars($product['name_product']); ?> - Shoes Shop</title>
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

	</head>
	<body>
		
	<div class="colorlib-loader"></div>

	<div id="page">
		<?php include 'View/user/header.php'; ?>

	
		<div class="colorlib-product">
			<div class="container">
				<div class="row row-pb-lg product-detail-wrap">
					<div class="col-sm-8">
                        <?php if (!empty($product['images'])): ?>
                            <div class="item">
								<div class="product-entry border">
									<a href="#" class="prod-img">
										<img src="/web_php_mvc/public/images/<?php echo htmlspecialchars($product['images'][0]['imageUrl']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($product['name_product']); ?>">
									</a>
								</div>
							</div>
                        <?php else: ?>
                        <div class="product-entry border">
                            <img src="/web_php_mvc/public/images/<?php echo htmlspecialchars($product['imageUrl'] ?? 'item-1.jpg'); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($product['name_product']); ?>">
                        </div>
                        <?php endif; ?>
					</div>
					<div class="col-sm-4">
						<div class="product-desc">
							<h3><?php echo htmlspecialchars($product['name_product']); ?></h3>
							<p class="price">
                                <?php if (!empty($product['promotions'])): ?>
                                    <span class="price-sale"><?php echo number_format($product['price'], 0, ',', '.'); ?> đ</span>
                                    <span><?php echo number_format($product['promotions'][0]['promotion_price'], 0, ',', '.'); ?> đ</span>
                                <?php else: ?>
                                    <span><?php echo number_format($product['price'], 0, ',', '.'); ?> đ</span>
                                <?php endif; ?>
                                <span class="rate">
                                    <i class="icon-star-full"></i>
                                    <i class="icon-star-full"></i>
                                    <i class="icon-star-full"></i>
                                    <i class="icon-star-full"></i>
                                    <i class="icon-star-full"></i>
                                </span>
							</p>
                            <p class="code">Mã SP: <?php echo htmlspecialchars($product['id_product']); ?></p>
							<p><?php echo htmlspecialchars($product['description'] ?? 'No description available.'); ?></p>
							<div class="size-wrap">
								<div class="block-26 mb-2">
									<h4>CHỌN SIZE GIÀY</h4>
                                    <?php if (!empty($product['available_sizes'])): ?>
				                    <ul>
                                        <?php foreach ($product['available_sizes'] as $size): ?>
				                        <li><a href="#" class="size-option" data-size-id="<?php echo $size['id_size']; ?>"><?php echo $size['size_value']; ?></a></li>
                                        <?php endforeach; ?>
				                    </ul>
                                    <?php else: ?>
                                        <p>No sizes available at the moment.</p>
                                    <?php endif; ?>
				                </div>
							</div>
                            <div class="input-group mb-4">
                                <span class="input-group-btn">
                                    <button type="button" class="quantity-left-minus btn" data-type="minus" data-field="">
                                        <i class="icon-minus2"></i>
                                    </button>
                                </span>
                                <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1" min="1" max="20">
                                <span class="input-group-btn ml-1">
                                    <button type="button" class="quantity-right-plus btn" data-type="plus" data-field="">
                                        <i class="icon-plus2"></i>
                                    </button>
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <small class="text-muted d-block mb-2">Tối đa 20 sản phẩm mỗi loại</small>
                                    <button id="add-to-cart" class="btn btn-primary btn-addtocart">
                                        <i class="icon-shopping-cart"> THÊM VÀO GIỎ</i> 
                                    </button>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-12 text-center">
                                    <button id="buy-now" class="btn btn-danger">MUA NGAY →</button>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-sm-12">
                                    <p>Hoặc đặt mua: <strong style="color: red;"><?php echo htmlspecialchars('0909300746'); ?></strong> (Tư vấn Miễn phí)</p>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-12">
                                    <div class="social-sharing">
                                        <a href="#" class="twitter"><i class="icon-twitter"></i></a>
                                        <a href="#" class="facebook"><i class="icon-facebook"></i></a>
                                        <a href="#" class="pinterest"><i class="icon-pinterest"></i></a>
                                        <a href="#" class="messenger"><i class="icon-chat"></i></a>
                                        <a href="#" class="zalo" style="font-weight: bold;">Zalo</a>
                                        <a href="#" class="back"><i class="icon-undo2"></i></a>
                                    </div>
                                </div>
                            </div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-12">
						<div class="row">
							<div class="col-md-12 pills">
								<div class="bd-example bd-example-tabs">
								    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
								        <li class="nav-item">
								            <a class="nav-link active" id="pills-description-tab" data-toggle="pill" href="#pills-description" role="tab" aria-controls="pills-description" aria-expanded="true">Description</a>
								        </li>
								        <li class="nav-item">
								            <a class="nav-link" id="pills-manufacturer-tab" data-toggle="pill" href="#pills-manufacturer" role="tab" aria-controls="pills-manufacturer" aria-expanded="true">Manufacturer</a>
								        </li>
								        <li class="nav-item">
								            <a class="nav-link" id="pills-review-tab" data-toggle="pill" href="#pills-review" role="tab" aria-controls="pills-review" aria-expanded="true">Review</a>
								        </li>
								    </ul>

								    <div class="tab-content" id="pills-tabContent">
								        <div class="tab-pane border fade show active" id="pills-description" role="tabpanel" aria-labelledby="pills-description-tab">
								            <p><?php echo htmlspecialchars($product['description'] ?? 'No description available.'); ?></p>
                                            <p>Material: <?php echo htmlspecialchars($product['material'] ?? 'Not specified'); ?></p>
                                            <p>Brand: <?php echo htmlspecialchars($product['name_brand'] ?? 'Not specified'); ?></p>
								        </div>

								        <div class="tab-pane border fade" id="pills-manufacturer" role="tabpanel" aria-labelledby="pills-manufacturer-tab">
								            <p>Brand: <?php echo htmlspecialchars($product['name_brand'] ?? 'Not specified'); ?></p>
								        </div>

								        <div class="tab-pane border fade" id="pills-review" role="tabpanel" aria-labelledby="pills-review-tab">
								            <div class="row">
								                <div class="col-md-12">
                                                    <p>No reviews yet for this product.</p>
								                </div>
								            </div>
								        </div>
								    </div>
								</div>
				            </div>
						</div>
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

    <script>
        $(document).ready(function(){
            var quantitiy = 0;
            var maxQuantity = 20;
            
            $('.quantity-right-plus').click(function(e){
                e.preventDefault();
                var quantity = parseInt($('#quantity').val());
                if(quantity < maxQuantity) {
                    $('#quantity').val(quantity + 1);
                } else {
                    alert('Số lượng đặt hàng tối đa là 20 sản phẩm');
                }
            });

            $('.quantity-left-minus').click(function(e){
                e.preventDefault();
                var quantity = parseInt($('#quantity').val());
                if(quantity > 1){
                    $('#quantity').val(quantity - 1);
                }
            });
            
            // Validate manual input
            $('#quantity').on('change', function() {
                var quantity = parseInt($(this).val());
                if(isNaN(quantity) || quantity < 1) {
                    $(this).val(1);
                } else if(quantity > maxQuantity) {
                    alert('Số lượng đặt hàng tối đa là 20 sản phẩm');
                    $(this).val(maxQuantity);
                }
            });
            
            // Size selection
            $('.size-option').click(function(e){
                e.preventDefault();
                $('.size-option').removeClass('active');
                $(this).addClass('active');
            });
            
            // Add to cart functionality
            $('#add-to-cart').click(function(e){
                e.preventDefault();
                var sizeId = $('.size-option.active').data('size-id');
                if (!sizeId) {
                    alert('Please select a size first.');
                    return;
                }
                
                var quantity = parseInt($('#quantity').val());
                if(quantity > maxQuantity) {
                    alert('Số lượng đặt hàng tối đa là 20 sản phẩm');
                    $('#quantity').val(maxQuantity);
                    return;
                }
                
                // AJAX request to add to cart
                $.ajax({
                    url: '/web_php_mvc/process_cart.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'add',
                        product_id: <?php echo $product['id_product']; ?>,
                        size_id: sizeId,
                        quantity: quantity
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            alert(response.message);
                            // Update cart count in header if needed
                            if (response.cart_count) {
                                // Assuming you have an element to display cart count
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
            });
            
            // Buy now functionality
            $('#buy-now').click(function(e){
                e.preventDefault();
                var sizeId = $('.size-option.active').data('size-id');
                if (!sizeId) {
                    alert('Vui lòng chọn kích cỡ trước khi đặt hàng.');
                    return;
                }
                
                var quantity = parseInt($('#quantity').val());
                if (isNaN(quantity) || quantity < 1) {
                    $('#quantity').val(1);
                    quantity = 1;
                }
                
                if(quantity > maxQuantity) {
                    alert('Số lượng đặt hàng tối đa là 20 sản phẩm. Không thể tiến hành thanh toán với số lượng này.');
                    $('#quantity').val(maxQuantity);
                    return;
                }
                
                // Check if adding this quantity would exceed the limit when combined with existing cart quantity
                $.ajax({
                    url: '/web_php_mvc/process_cart.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'check_quantity',
                        product_id: <?php echo $product['id_product']; ?>,
                        size_id: sizeId,
                        quantity: quantity
                    },
                    success: function(response) {
                        if (response.status === 'exceeded') {
                            alert('Bạn đã có ' + response.current_quantity + ' sản phẩm này trong giỏ hàng. Tổng số lượng không được vượt quá 20. Vui lòng điều chỉnh số lượng.');
                            return;
                        }
                        
                        // Add product to cart
                        $.ajax({
                            url: '/web_php_mvc/process_cart.php',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                action: 'add',
                                product_id: <?php echo $product['id_product']; ?>,
                                size_id: sizeId,
                                quantity: quantity
                            },
                            success: function(response) {
                                if (response.status === 'success' || response.status === 'warning') {
                                    // Redirect to checkout page
                                    window.location.href = '/web_php_mvc/View/user/payment.php';
                                } else {
                                    alert(response.message);
                                }
                            },
                            error: function() {
                                alert('Đã xảy ra lỗi. Vui lòng thử lại sau.');
                            }
                        });
                    },
                    error: function() {
                        alert('Đã xảy ra lỗi khi kiểm tra số lượng. Vui lòng thử lại sau.');
                    }
                });
            });
        });
    </script>

	</body>
</html> 