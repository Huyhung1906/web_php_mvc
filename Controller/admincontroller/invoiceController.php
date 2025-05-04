<?php
session_start();
include '../../config/config.php';
include '../../model/adminmodel.php';
include '../../model/Invoice.php';


if (!isset($_SESSION['id_role']) || $_SESSION['id_role'] != 1) {
	header("Location: ../view/auth/login.php");
	exit();
}

$model = new InvoiceModel($conn);

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
$invoice = $model->getInvoices($search);