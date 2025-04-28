<!DOCTYPE HTML>
<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../Controller/ProductFilterController.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Initialize ProductFilterController and get filtered sneakers
$filterController = new ProductFilterController();
$result = $filterController->getFilteredSneakers();
$sneakers = $result['products'];
$sizes = $result['sizes'];

// Check if user is logged in
$isLoggedIn = isset($_SESSION['id_user']) && !empty($_SESSION['id_user']);
$username = $isLoggedIn ? $_SESSION['username'] : '';

// Remove direct access to session variables without checking
// $userId = $_SESSION['id_user'];
// $username = $_SESSION['username'];
?>
<html>
	<head>
	<title>Sneakers - Footwear Store</title>
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
		<?php include 'header.php'; ?>

		<!-- Filter Section -->
		<div class="colorlib-product">
			<div class="container">
				<div class="row">
					<?php include 'filter.php'; ?>

					<div class="col-lg-9 col-xl-9">
						<div class="row row-pb-md">
							<?php if (isset($sneakers) && is_array($sneakers) && !empty($sneakers)): ?>
								<?php foreach ($sneakers as $sneaker): ?>
									<div class="col-lg-4 mb-4 text-center">
										<div class="product-entry border">
											<a href="/web_php_mvc/index.php?url=product-detail/<?php echo htmlspecialchars($sneaker['id_product']); ?>" class="prod-img">
												<?php if (isset($sneaker['imageUrl']) && !empty($sneaker['imageUrl'])): ?>
													<img src="/web_php_mvc/public/images/<?php echo htmlspecialchars($sneaker['imageUrl']); ?>" 
														class="img-fluid" alt="<?php echo htmlspecialchars($sneaker['name_product']); ?>">
												<?php else: ?>
													<img src="/web_php_mvc/public/images/item-1.jpg" class="img-fluid" alt="Default Image">
												<?php endif; ?>
											</a>
											<div class="desc">
												<h2><a href="/web_php_mvc/index.php?url=product-detail/<?php echo htmlspecialchars($sneaker['id_product']); ?>">
													<?php echo htmlspecialchars($sneaker['name_product']); ?>
												</a></h2>
												<span class="price"><?php echo number_format($sneaker['price'], 0, ',', '.'); ?>đ</span>
												<?php if (isset($sneaker['original_price']) && $sneaker['original_price'] > $sneaker['price']): ?>
													<span class="original-price"><del><?php echo number_format($sneaker['original_price'], 0, ',', '.'); ?>đ</del></span>
												<?php endif; ?>
											</div>
										</div>
									</div>
								<?php endforeach; ?>
							<?php else: ?>
								<div class="col-12 text-center">
									<p>No sneakers available at the moment.</p>
								</div>
							<?php endif; ?>
						</div>
						
						<!-- Pagination -->
						<div class="row">
							<div class="col-md-12 text-center">
								<div class="block-27">
									<ul>
										<li><a href="#">&lt;</a></li>
										<li class="active"><span>1</span></li>
										<li><a href="#">2</a></li>
										<li><a href="#">3</a></li>
										<li><a href="#">4</a></li>
										<li><a href="#">5</a></li>
										<li><a href="#">&gt;</a></li>
									</ul>
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

	</body>
</html>
