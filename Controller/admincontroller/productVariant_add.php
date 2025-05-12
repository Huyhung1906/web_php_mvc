<?php
require_once(__DIR__ . '/../../Model/productVariant.php');
$model = new ProductVariantModel();

$error = '';
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
    // Kiểm tra biến thể đã tồn tại chưa
    if ($model->checkVariantExists($data['product'], $data['size'], $data['color'])) {
        $error = 'Biến thể này đã tồn tại!';
    } else {
        $model->addVariant($data);
        header('Location: product_variants.php?success=1');
        exit;
    }
}
?> 