<?php
require_once(__DIR__ . '/../../Model/warranty.php');
$model = new WarrantyModel();

$repairStatusOptions = [
    'Đã gửi đơn', 'Xác nhận', 'Từ chối bảo hành', 'Đang bảo hành', 'Đang gửi hàng', 'Hoàn thành'
];

// Xử lý filter
$filter_status = $_GET['repair_status'] ?? '';

// Xử lý ngày tháng
$filter_from = '';
$filter_to = '';

if (!empty($_GET['from_date'])) {
    $from_date = DateTime::createFromFormat('Y-m-d', $_GET['from_date']);
    if ($from_date) {
        $filter_from = $from_date->format('Y-m-d');
    }
}

if (!empty($_GET['to_date'])) {
    $to_date = DateTime::createFromFormat('Y-m-d', $_GET['to_date']);
    if ($to_date) {
        $filter_to = $to_date->format('Y-m-d');
    }
}

// Debug filter values
error_log("Filter values:");
error_log("Status: " . $filter_status);
error_log("From date: " . $filter_from);
error_log("To date: " . $filter_to);

// Xử lý lưu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_warrantydetail'])) {
    $id = $_POST['id_warrantydetail'];
    $status = $_POST['repair_status'];
    $note = $_POST['notes'];
    $cost = $_POST['cost'] ?? null;
    $model->updateWarrantyDetail($id, $status, $note, $cost);
    header('Location: warranty.php?saved=1');
    exit;
}

// Lấy dữ liệu chi tiết bảo hành
$warrantyDetails = $model->getWarrantyDetails($filter_status, $filter_from, $filter_to);
?>
