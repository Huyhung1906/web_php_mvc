<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../Model/InvoiceDetail.php';
require_once __DIR__ . '/../../Model/Order.php';
require_once __DIR__ . '/../../Model/Product.php';

// Session check
if (session_status() == PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit();
}
$userId = $_SESSION['id_user'];

// Lấy tất cả đơn hàng đã mua
$orderModel = new Order();
$orders = $orderModel->getOrdersByUser($userId);

// Kết nối DB để lưu và lấy warranty
$conn = $conn ?? (include __DIR__ . '/../../config/config.php');

// Lưu warranty nếu chưa có
foreach ($orders as $order) {
    foreach ($order['items'] as $item) {
        $id_variant = $item['id_variant'];
        $start_date = $order['InvoiceDate'];
        $end_date = date('Y-m-d', strtotime($start_date . ' +1 month'));
        $status = 'Active';

        // Kiểm tra đã có warranty chưa
        $stmt = $conn->prepare("SELECT * FROM warranty WHERE id_invoice=? AND id_variant=?");
        $stmt->execute([$order['id_invoice'], $id_variant]);
        if ($stmt->rowCount() == 0) {
            $stmt = $conn->prepare("INSERT INTO warranty (id_invoice, id_variant, warranty_start_date, warranty_end_date, warranty_status) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$order['id_invoice'], $id_variant, $start_date, $end_date, $status]);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['warranty_request_submit'])) {
    $id_warranty = $_POST['id_warranty'];
    $repair_description = trim($_POST['repair_description']);
    $repair_date = date('Y-m-d');
    $repair_status = 'Đã gửi đơn';
    $cost = 0;
    $notes = '';

    $stmt = $conn->prepare("INSERT INTO warrantydetail (id_warranty, repair_date, repair_description, repair_status, cost, notes) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$id_warranty, $repair_date, $repair_description, $repair_status, $cost, $notes]);
}

// Lấy danh sách warranty của user hiện tại (join invoice để lọc theo user)
$stmt = $conn->prepare("
    SELECT w.*, p.name_product 
    FROM warranty w
    JOIN invoice i ON w.id_invoice = i.id_invoice
    JOIN product_variant v ON w.id_variant = v.id_variant
    JOIN product p ON v.id_product = p.id_product
    WHERE i.id_user = ?
");
$stmt->execute([$userId]);
$warranties = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy tất cả id_warranty của user hiện tại
$userWarrantyIds = array_column($warranties, 'id_warranty');
$warrantyDetails = [];
if (!empty($userWarrantyIds)) {
    $in = str_repeat('?,', count($userWarrantyIds) - 1) . '?';
    $stmt = $conn->prepare("
        SELECT d.*, w.id_warranty, p.name_product
        FROM warrantydetail d
        JOIN warranty w ON d.id_warranty = w.id_warranty
        JOIN product_variant v ON w.id_variant = v.id_variant
        JOIN product p ON v.id_product = p.id_product
        WHERE d.id_warranty IN ($in)
        ORDER BY d.repair_date DESC
    ");
    $stmt->execute($userWarrantyIds);
    $warrantyDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// var_dump($warranties); exit;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Warranty Service</title>
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
                <p class="bread"><span><a href="/web_php_mvc/">Home</a></span> / <span>Warranty</span></p>
            </div>
        </div>
    </div>
</div>

<div class="colorlib-product">
    <div class="container" style="margin-top: 20px; margin-bottom: 40px;">
        <h2>Warranty Service</h2>
        <div class="mb-5">
            <h4>All Purchased Products</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($warranties as $w): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($w['name_product']); ?></td>
                        <td><?php echo htmlspecialchars($w['warranty_start_date']); ?></td>
                        <td><?php echo htmlspecialchars($w['warranty_end_date']); ?></td>
                        <td>
                            <?php
                                $now = date('Y-m-d');
                                if ($now <= $w['warranty_end_date']) {
                                    echo '<span style="color: #28a745; font-weight: bold;">Active</span>';
                                } else {
                                    echo '<span style="color: #dc3545; font-weight: bold;">Expired</span>';
                                }
                            ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="text-center my-3">
                <button class="btn btn-success" data-toggle="modal" data-target="#warrantyRequestModal">Create Warranty Request</button>
            </div>
        </div>

        <!-- Modal for Warranty Request -->
        <div class="modal fade" id="warrantyRequestModal" tabindex="-1" role="dialog" aria-labelledby="warrantyRequestModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <form method="post">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="warrantyRequestModalLabel">Create Warranty Request</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <label for="id_warranty">Select product:</label>
                  <select name="id_warranty" id="id_warranty" class="form-control mb-2" required>
                    <option value="">-- Select product --</option>
                    <?php foreach ($warranties as $w): if (date('Y-m-d') <= $w['warranty_end_date']) { ?>
                      <option value="<?php echo $w['id_warranty']; ?>">
                        <?php echo htmlspecialchars($w['name_product'] . ' (Start: ' . $w['warranty_start_date'] . ', End: ' . $w['warranty_end_date'] . ')'); ?>
                      </option>
                    <?php } endforeach; ?>
                  </select>
                  <label for="repair_description">Repair description:</label>
                  <textarea name="repair_description" id="repair_description" class="form-control" required></textarea>
                </div>
                <div class="modal-footer">
                  <button type="submit" name="warranty_request_submit" class="btn btn-primary">Submit Request</button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div>
            <h4>Your Warranty Requests</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Repair Date</th>
                        <th>Product</th>
                        <th>Repair Description</th>
                        <th>Status</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $stt=1; foreach ($warrantyDetails as $d): ?>
                    <tr>
                        <td><?php echo $stt++; ?></td>
                        <td><?php echo htmlspecialchars($d['repair_date']); ?></td>
                        <td><?php echo htmlspecialchars($d['name_product']); ?></td>
                        <td><?php echo htmlspecialchars($d['repair_description']); ?></td>
                        <td><?php echo htmlspecialchars($d['repair_status']); ?></td>
                        <td><?php echo htmlspecialchars($d['notes']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<div class="gototop js-top">
    <a href="#" class="js-gotop"><i class="ion-ios-arrow-up"></i></a>
</div>
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
$(window).on('load', function() {
    $('.colorlib-loader').fadeOut('slow');
});
</script>
</body>
</html>
