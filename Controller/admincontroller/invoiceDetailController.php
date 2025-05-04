<?php
require_once('../../model/InvoiceDetail.php');
$detailModel = new InvoiceDetailModel();

// Xử lý AJAX lấy giá variant
if (isset($_GET['ajax']) && $_GET['ajax'] === 'get_price' && isset($_GET['id_variant'])) {
    $id_variant = $_GET['id_variant'];
    $price = $detailModel->getPriceByVariant($id_variant);
    echo json_encode(['price' => $price]);
    exit;
}

$id_invoice = $_GET['id'] ?? '';

// Lấy chi tiết hóa đơn
$details = [];
if ($id_invoice) {
    $details = $detailModel->getInvoiceDetails($id_invoice);
}

// Lấy danh sách variant
$variants = [];
$variants = $detailModel->getAllVariants($id_invoice);

// Thêm chi tiết hóa đơn
if (isset($_POST['add_detail'])) {
    $id_variant = $_POST['id_variant'] ?? '';
    $quantity = $_POST['quantity'] ?? '';
    $sub_total = $_POST['sub_total'] ?? '';
    if ($id_invoice && $id_variant && $quantity && $sub_total) {
        $detailModel->addInvoiceDetail($id_invoice, $id_variant, $quantity, $sub_total);
        $detailModel->updateInvoiceTotalAmount($id_invoice);
        header("Location: invoice_detail.php?id=$id_invoice");
        exit();
    }
}

// Xóa chi tiết hóa đơn
if (isset($_GET['delete_detail'])) {
    $id_variant = $_GET['delete_detail'];
    $detailModel->deleteInvoiceDetail($id_invoice, $id_variant);
    $detailModel->updateInvoiceTotalAmount($id_invoice);
    header("Location: invoice_detail.php?id=$id_invoice");
    exit();
}
