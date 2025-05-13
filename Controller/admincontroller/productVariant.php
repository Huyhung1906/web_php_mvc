<?php
require_once(__DIR__ . '/../../Model/productVariant.php');

    $model = new ProductVariantModel();

    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $model->deleteVariant($id);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    
    $filter_product = $_GET['product'] ?? '';
    $filter_size = $_GET['size'] ?? '';
    $filter_color = $_GET['color'] ?? '';
    $search = $_GET['search'] ?? '';

    $products = $model->getAllProducts();
    $sizes = $model->getAllSizes();
    $colors = $model->getAllColors();
    $variants = $model->getVariants($filter_product, $filter_size, $filter_color, $search);


?>
