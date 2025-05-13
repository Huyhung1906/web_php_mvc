<?php
require_once(__DIR__ . '/../../Model/productVariant.php');
$model = new ProductVariantModel();

$error = '';
$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: product_variants.php');
    exit;
}

$variant = $model->getVariantById($id);
if (!$variant) {
    $error = 'Không tìm thấy biến thể!';
}

$products = $model->getAllProducts();
$sizes = $model->getAllSizes();
$colors = $model->getAllColors();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'product' => $_POST['product'],
        'size' => $_POST['size'],
        'color' => $_POST['color'],
        'quantity' => $_POST['quantity'],
        'expired' => $_POST['expired_date']
    ];
    $model->updateVariant($id, $data);
    header('Location: product_variants.php?success=2');
    exit;
}
?> 