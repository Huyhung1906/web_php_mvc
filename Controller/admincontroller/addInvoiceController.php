<?php
require_once('../../model/Invoice.php');
require_once('../../model/adminmodel.php');
session_start();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_POST['id_user'] ?? '';
    $CustomerName = $_POST['CustomerName'] ?? '';
    $CustomerPhone = $_POST['CustomerPhone'] ?? '';
    $InvoiceDate = date('Y-m-d');
    $Status = $_POST['Status'] ?? '';
    $CustomerAddress = $_POST['CustomerAddress'] ?? '';

    if ($id_user && $CustomerName && $CustomerPhone && $Status) {
        $invoiceModel = new InvoiceModel();
        $result = $invoiceModel->addInvoice($id_user, $CustomerName, $CustomerPhone, $InvoiceDate, $Status, $CustomerAddress);
        if ($result) {
            $_SESSION['success_message'] = 'Thêm hóa đơn thành công!';
            header('Location: invoice.php');
            exit();
        } else {
            $error = 'Lỗi khi thêm hóa đơn!';
        }
    } else {
        $error = 'Vui lòng nhập đầy đủ thông tin!';
    }
}