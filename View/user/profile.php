<!DOCTYPE HTML>
<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../Model/user.php';
require_once __DIR__ . '/../../Model/Order.php';

// Initialize session if not already started
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

// Check if user is logged in
if (!isset($_SESSION['id_user'])) {
	header("Location: ../auth/login.php");
	exit();
}

$userId = $_SESSION['id_user'];
$username = $_SESSION['username'];

// Get user addresses
$userModel = new UserModel();
$userAddresses = $userModel->getUserAddresses($userId);

// Get user orders
$orderModel = new Order();
$userOrders = $orderModel->getOrdersByUser($userId);
?>
<html>

<head>
	<title>User Profile - Footwear</title>
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

		<div class="breadcrumbs">
			<div class="container">
				<div class="row">
					<div class="col">
						<p class="bread"><span><a href="index.php">Home</a></span> / <span>Profile</span></p>
					</div>
				</div>
			</div>
		</div>

		<div class="colorlib-product profile-section">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<h2 class="mb-4">My Profile</h2>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="profile-card">
							<h3>Account Information</h3>
							<p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
							<p><strong>Account ID:</strong> <?php echo htmlspecialchars($userId); ?></p>
							<p><strong>Account Type:</strong> <?php echo ($_SESSION['id_role'] == 1) ? 'Administrator' : 'User'; ?></p>
						</div>
					</div>
					<div class="col-md-8">
						<div class="profile-card">
							<h3>Recent Orders</h3>
							<?php if (empty($userOrders)): ?>
								<p>You have not placed any orders yet.</p>
								<a href="index.php" class="btn btn-primary">Shop now</a>
							<?php else: ?>
								<div class="table-responsive">
									<table class="table table-bordered">
										<thead>
											<tr>
												<th>Order ID</th>
												<th>Date</th>
												<th>Shipping Address</th>
												<th>Payment Method</th>
												<th>Total</th>
												<th>Details</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($userOrders as $order): ?>
												<tr>
													<td><?php echo $order['id']; ?></td>
													<td><?php echo $order['created_at']; ?></td>
													<td><?php echo htmlspecialchars($order['address']); ?></td>
													<td><?php echo htmlspecialchars($order['payment_method']); ?></td>
													<td><?php echo number_format($order['total'], 0, ',', '.'); ?> đ</td>
													<td>
														<ul style="padding-left: 18px; margin-bottom: 0;">
															<?php foreach ($order['items'] as $item): ?>
																<li>
																	<?php echo htmlspecialchars($item['product_name']); ?>
																	(Qty: <?php echo $item['quantity']; ?>, Price: <?php echo number_format($item['price'], 0, ',', '.'); ?> đ)
																	<button class="btn btn-sm btn-info warranty-btn"
																		data-order-item-id="<?php echo $item['id']; ?>"
																		data-product-name="<?php echo htmlspecialchars($item['product_name']); ?>">
																		Warranty
																	</button>
																</li>
															<?php endforeach; ?>
														</ul>
													</td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="profile-card">
							<h3>Address Information</h3>
							<?php if (isset($_SESSION['address_error'])): ?>
								<div class="alert alert-danger">
									<?php echo htmlspecialchars($_SESSION['address_error']); ?>
									<?php unset($_SESSION['address_error']); ?>
								</div>
							<?php endif; ?>

							<?php if (isset($_SESSION['address_success'])): ?>
								<div class="alert alert-success">
									<?php echo htmlspecialchars($_SESSION['address_success']); ?>
									<?php unset($_SESSION['address_success']); ?>
								</div>
							<?php endif; ?>

							<?php if (empty($userAddresses)): ?>
								<p>You don't have any saved addresses.</p>
							<?php else: ?>
								<div class="table-responsive">
									<table class="table table-bordered">
										<thead>
											<tr>
												<th>Province/city</th>
												<th>District</th>
												<th>Ward</th>
												<th>Street</th>
												<th>Type</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($userAddresses as $address): ?>
												<tr>
													<td><?php echo htmlspecialchars($address['province'] ?? 'N/A'); ?></td>
													<td><?php echo htmlspecialchars($address['district'] ?? 'N/A'); ?></td>
													<td><?php echo htmlspecialchars($address['ward'] ?? 'N/A'); ?></td>
													<td><?php echo htmlspecialchars($address['street'] ?? 'N/A'); ?></td>
													<td><?php echo htmlspecialchars($address['address_type'] ?? 'N/A'); ?></td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							<?php endif; ?>

							<h4 class="mt-4">Add New Address/Or Edit Address</h4>
							<form action="/web_php_mvc/View/user/add-address.php" method="post" class="mt-3">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="province">Province</label>
											<input type="text" name="province" id="province" class="form-control" required>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="district">District</label>
											<input type="text" name="district" id="district" class="form-control" required>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="ward">Ward</label>
											<input type="text" name="ward" id="ward" class="form-control" required>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="street">Street</label>
											<input type="text" name="street" id="street" class="form-control" required>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="address_type">Address Type</label>
											<select name="address_type" id="address_type" class="form-control" required>
												<option value="Nhà riêng">Nhà riêng</option>
												<option value="Công ty">Công ty</option>
												<option value="Khác">Khác</option>
											</select>
										</div>
									</div>
								</div>
								<div class="row mt-3">
									<div class="col-md-12">
										<button type="submit" class="btn btn-primary">Save Address</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="profile-card">
							<h3>Account Actions</h3>
							<a href="../auth/logout.php" class="btn btn-danger">Logout</a>
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
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

	<!-- Waypoints -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>

	<!-- Các plugin khác -->
	<script src="/web_php_mvc/public/js/popper.min.js"></script>
	<script src="/web_php_mvc/public/js/bootstrap.min.js"></script>
	<script src="/web_php_mvc/public/js/jquery.easing.1.3.js"></script>
	<script src="/web_php_mvc/public/js/jquery.flexslider-min.js"></script>
	<script src="/web_php_mvc/public/js/owl.carousel.min.js"></script>
	<script src="/web_php_mvc/public/js/jquery.magnific-popup.min.js"></script>
	<script src="/web_php_mvc/public/js/magnific-popup-options.js"></script>
	<script src="/web_php_mvc/public/js/bootstrap-datepicker.js"></script>
	<script src="/web_php_mvc/public/js/jquery.stellar.min.js"></script>

	<!-- Cuối cùng là main.js -->
	<script src="/web_php_mvc/public/js/main.js"></script>


	<!-- Warranty Modal -->
	<div class="modal fade" id="warrantyModal" tabindex="-1" role="dialog" aria-labelledby="warrantyModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="warrantyModalLabel">Warranty Information</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" id="warrantyModalBody">
					<!-- Nội dung sẽ được load bằng AJAX -->
				</div>
			</div>
		</div>
	</div>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
	<script src="/web_php_mvc/public/js/magnific-popup-options.js"></script>
	<script>
		$(function() {
			$('.warranty-btn').on('click', function() {
				var orderItemId = $(this).data('order-item-id');
				var productName = $(this).data('product-name');
				// Gọi AJAX để lấy thông tin bảo hành
				$.post('/web_php_mvc/View/user/warranty_info.php', {
					order_item_id: orderItemId
				}, function(data) {
					$('#warrantyModalLabel').text('Warranty for: ' + productName);
					$('#warrantyModalBody').html(data);
					$('#warrantyModal').modal('show');
				});
			});
		});
	</script>

</body>

</html>