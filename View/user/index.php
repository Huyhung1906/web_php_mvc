<!DOCTYPE HTML>
<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../Model/Product.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
?>
<html>
	<head>
	<title>Footwear - Free Bootstrap 4 Template by Colorlib</title>
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
		.product-entry.border {
			transition: border-color 0.3s ease;
		}
		.product-entry.border:hover {
			border-color: #87F9E4 !important;
		}
	</style>

	</head>
	<body>
		
	<div class="colorlib-loader"></div>

	<div id="page">
		<?php include 'header.php'; ?>
		
		<aside id="colorlib-hero">
			<div class="flexslider">
				<ul class="slides">
			   	<li style="background-image: url(/web_php_mvc/public/images/adidas-background.jpg);">
			   		<div class="overlay"></div>
			   		<div class="container-fluid">
			   			<div class="row">
				   			<div class="col-sm-6 offset-sm-3 text-center slider-text">
				   				<div class="slider-text-inner">
				   					<div class="desc">
					   					<h1 class="head-1">Footwear's</h1>
					   					<h2 class="head-2">Shoes</h2>
					   					<h2 class="head-3">Collection</h2>
					   					<p class="category"><span>New trending shoes</span></p>
					   					<p><a href="#" class="btn btn-primary">Shop Collection</a></p>
				   					</div>
				   				</div>
				   			</div>
				   		</div>
			   		</div>
			   	</li>
			   	<li style="background-image: url(/web_php_mvc/public/images/nike-background.jpg);">
			   		<div class="overlay"></div>
			   		<div class="container-fluid">
			   			<div class="row">
				   			<div class="col-sm-6 offset-sm-3 text-center slider-text">
				   				<div class="slider-text-inner">
				   					<div class="desc">
					   					<h1 class="head-1">Huge</h1>
					   					<h2 class="head-2">Sale</h2>
					   					<h2 class="head-3"><strong class="font-weight-bold">50%</strong> Off</h2>
					   					<p class="category"><span>Big sale sandals</span></p>
					   					<p><a href="#" class="btn btn-primary">Shop Collection</a></p>
				   					</div>
				   				</div>
				   			</div>
				   		</div>
			   		</div>
			   	</li>
			   	<li style="background-image: url(/web_php_mvc/public/images/img_bg_3.jpg);">
			   		<div class="overlay"></div>
			   		<div class="container-fluid">
			   			<div class="row">
				   			<div class="col-sm-6 offset-sm-3 text-center slider-text">
				   				<div class="slider-text-inner">
				   					<div class="desc">
					   					<h1 class="head-1">New</h1>
					   					<h2 class="head-2">Arrival</h2>
					   					<h2 class="head-3">up to <strong class="font-weight-bold">30%</strong> off</h2>
					   					<p class="category"><span>New stylish shoes for men</span></p>
					   					<p><a href="#" class="btn btn-primary">Shop Collection</a></p>
				   					</div>
				   				</div>
				   			</div>
				   		</div>
			   		</div>
			   	</li>
			  	</ul>
		  	</div>
		</aside>
		<div class="colorlib-intro">
			<div class="container">
				<div class="row">
					<div class="col-sm-12 text-center">
						<h2 class="intro">It started with a simple idea: Create quality, well-designed products that I wanted myself.</h2>
					</div>
				</div>
			</div>
		</div>
		<div class="colorlib-product">
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-6 text-center">
						<div class="featured">
							<a href="#" class="featured-img" style="background-image: url(/web_php_mvc/public/images/nike-collection.png);"></a>
							<div class="desc">
								<h2><a href="#">Nike's collection</a></h2>
							</div>
						</div>
					</div>
					<div class="col-sm-6 text-center">
						<div class="featured">
							<a href="#" class="featured-img" style="background-image: url(/web_php_mvc/public/images/addidas-collection.jpg);"></a>
							<div class="desc">
								<h2><a href="#">Addidas's Collection</a></h2>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="colorlib-product">
			<div class="container">
				<div class="row">
					<div class="col-sm-8 offset-sm-2 text-center colorlib-heading">
						<h2>Best Sellers</h2>
					</div>
				</div>
				<div class="row row-pb-md">
					<?php if (isset($products) && is_array($products) && !empty($products)): ?>
						<?php foreach ($products as $product): ?>
							<div class="col-lg-3 mb-4 text-center">
								<div class="product-entry border">
									<a href="/web_php_mvc/index.php?url=product-detail/<?php echo htmlspecialchars($product['id_product']); ?>" class="prod-img">
										<?php if (isset($product['imageUrl']) && !empty($product['imageUrl'])): ?>
											<img src="/web_php_mvc/public/images/<?php echo $product['imageUrl']; ?>" 
												 class="img-fluid" alt="<?php echo htmlspecialchars($product['name_product'] ?? 'Product Image'); ?>">
										<?php else: ?>
											<img src="/web_php_mvc/public/images/item-1.jpg" 
												 class="img-fluid" alt="No Image Available">
										<?php endif; ?>
									</a>
									<div class="desc">
										<h2><a href="/web_php_mvc/index.php?url=product-detail/<?php echo htmlspecialchars($product['id_product']); ?>">
											<?php echo htmlspecialchars($product['name_product'] ?? 'Product Name Not Available'); ?>
										</a></h2>
										<span class="price"><?php echo number_format($product['price'] ?? 0, 0, ',', '.'); ?> đ</span>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					<?php else: ?>
						<div class="col-12 text-center">
							<p>No products available at the moment.</p>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="colorlib-partner">
			<div class="container">
				<div class="row">
					<div class="col-sm-8 offset-sm-2 text-center colorlib-heading colorlib-heading-sm">
						<h2>Trusted Partners</h2>
					</div>
				</div>
				<div class="row">
					<div class="col partner-col text-center">
						<img src="/web_php_mvc/public/images/brand-1.jpg" class="img-fluid" alt="Free html4 bootstrap 4 template">
					</div>
					<div class="col partner-col text-center">
						<img src="/web_php_mvc/public/images/brand-2.jpg" class="img-fluid" alt="Free html4 bootstrap 4 template">
					</div>
					<div class="col partner-col text-center">
						<img src="/web_php_mvc/public/images/brand-3.jpg" class="img-fluid" alt="Free html4 bootstrap 4 template">
					</div>
					<div class="col partner-col text-center">
						<img src="/web_php_mvc/public/images/brand-4.jpg" class="img-fluid" alt="Free html4 bootstrap 4 template">
					</div>
					<div class="col partner-col text-center">
						<img src="/web_php_mvc/public/images/brand-5.jpg" class="img-fluid" alt="Free html4 bootstrap 4 template">
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

