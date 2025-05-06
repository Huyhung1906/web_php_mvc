<?php
session_start();
include '../../config/config.php';
include '../../model/adminmodel.php';
include '../../model/Invoice.php';


if (!isset($_SESSION['id_role']) || $_SESSION['id_role'] == 3 ) {
	header("Location: ../auth/login.php");
	exit();
}

$model = new InvoiceModel($conn);
$check = new AdminModel($conn);
// Xử lý cập nhật trạng thái qua AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_invoice']) && isset($_POST['status'])) {
    $id_invoice = $_POST['id_invoice'];
    $status = $_POST['status'];
    $result = $model->updateInvoiceStatus($id_invoice, $status);
    echo $result ? 'success' : 'fail';
    exit();
}

// Xử lý xóa
if (isset($_GET['delete'])) {
	$model->deleteInvoice($_GET['delete']);
	header("Location: invoice.php");
	exit();
}
// Xử lý tìm kiếm
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : '';
$from_date = isset($_GET['from_date']) ? trim($_GET['from_date']) : '';
$to_date = isset($_GET['to_date']) ? trim($_GET['to_date']) : '';
$address = isset($_GET['address']) ? trim($_GET['address']) : '';
$province = isset($_GET['province']) ? trim($_GET['province']) : '';
$district = isset($_GET['district']) ? trim($_GET['district']) : '';
$invoice = $model->getInvoices($search, $status, $from_date, $to_date, $province, $district);

// Lấy danh sách tỉnh/thành phố và quận/huyện từ bảng address
$provinces = [];
$districtsByProvince = [];
try {
    $stmt = $conn->prepare("SELECT DISTINCT province FROM address WHERE province IS NOT NULL AND province != ''");
    $stmt->execute();
    $provinces = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'province');

    $stmt2 = $conn->prepare("SELECT DISTINCT province, district FROM address WHERE province IS NOT NULL AND province != '' AND district IS NOT NULL AND district != ''");
    $stmt2->execute();
    $districtsByProvince = [];
    foreach ($stmt2->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $province = $row['province'];
        $district = $row['district'];
        if (!isset($districtsByProvince[$province])) $districtsByProvince[$province] = [];
        if (!in_array($district, $districtsByProvince[$province])) $districtsByProvince[$province][] = $district;
    }
} catch (Exception $e) {
    $provinces = [];
    $districtsByProvince = [];
}