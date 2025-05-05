<?php
require_once('../../config/config.php');
require_once('../../Model/PromotionProduct.php');
require_once('../../Model/Promotion.php');
require_once('../../Model/Product.php');

$model = new PromotionProductModel();

// Lấy tất cả sản phẩm khuyến mãi (cho view tổng hợp)
if (isset($_GET['all'])) {
    $all_promotion_products = $model->getAllPromotionProducts();
    // Có thể require view tại đây nếu muốn
    require('../../View/admin/promotionproduct.php');
    exit();
}

// Thêm sản phẩm vào khuyến mãi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_promotion'], $_POST['product_ids'])) {
    $id_promotion = intval($_POST['id_promotion']);
    $product_ids = $_POST['product_ids'];
    $promotion_prices = $_POST['promotion_price'];

    foreach ($product_ids as $id_product) {
        $promotion_price = isset($promotion_prices[$id_product]) ? floatval($promotion_prices[$id_product]) : 0;
        $model->addPromotionProduct($id_promotion, $id_product, $promotion_price);
    }
    header('Location: ../../View/admin/promotion_product.php');
    exit();
}

// Cập nhật giá khuyến mãi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_price'], $_POST['id_promotion'], $_POST['id_product'], $_POST['promotion_price'])) {
    $id_promotion = intval($_POST['id_promotion']);
    $id_product = intval($_POST['id_product']);
    $promotion_price = floatval($_POST['promotion_price']);
    $model->updatePromotionPrice($id_promotion, $id_product, $promotion_price);
    header('Location: ../admin/promotion_product.php?id_promotion=' . $id_promotion);
    exit();
}

// Xóa sản phẩm khỏi khuyến mãi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'], $_POST['id_promotion'], $_POST['id_product'])) {
    $id_promotion = intval($_POST['id_promotion']);
    $id_product = intval($_POST['id_product']);
    $model->deletePromotionProduct($id_promotion, $id_product);
    header('Location: ../../View/admin/promotion_product.php');
    exit();
}

// Đổi khuyến mãi cho sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_product'], $_POST['id_promotion'])) {
    $id_product = intval($_POST['id_product']);
    $id_promotion = intval($_POST['id_promotion']);

    $promotionModel = new PromotionModel();
    $productModel = new Product();

    $promotion = $promotionModel->getPromotionById($id_promotion);
    $product = $productModel->getProductById($id_product);

    if ($promotion && $product) {
        $price = $product['price'];
        if ($promotion['discount_type'] == 'percentage') {
            $promotion_price = $price * (1 - $promotion['discount_value'] / 100);
        } else { // fixed
            $promotion_price = $price - $promotion['discount_value'];
        }
        $model->updatePromotionForProduct($id_product, $id_promotion, $promotion_price);
    }
    header('Location: ../../View/admin/promotion_product.php');
    exit();
}

// Nếu không có thao tác hợp lệ, quay lại danh sách khuyến mãi
header('Location: ../../View/admin/promotion_product.php');
exit();