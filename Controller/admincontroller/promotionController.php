<?php
session_start();
include '../../config/config.php';
include '../../model/adminmodel.php';
include '../../Model/Promotion.php';

if (!isset($_SESSION['id_role']) || $_SESSION['id_role'] == 3) {
    header("Location: ../auth/login.php");
    exit();
}

$model = new PromotionModel($conn);
$check = new AdminModel($conn);

// Xử lý cập nhật trạng thái qua AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_promotions'])) {
    $id = $_POST['id_promotions'];
    if (isset($_POST['status'])) {
        $status = $_POST['status'];
        // Lấy thông tin cũ
        $promotion = $model->getPromotionById($id);
        // Cập nhật chỉ trường status, giữ nguyên các trường khác
        $model->updatePromotion(
            $id,
            $promotion['name_promotion'],
            $promotion['start_date'],
            $promotion['end_date'],
            $promotion['discount_type'],
            $promotion['discount_value'],
            $promotion['description'],
            $status
        );
        echo 'success';
        exit();
    }
    if (isset($_POST['discount_type'])) {
        $discount_type = $_POST['discount_type'];
        // Lấy thông tin cũ
        $promotion = $model->getPromotionById($id);
        // Cập nhật chỉ trường discount_type, giữ nguyên các trường khác
        $model->updatePromotion(
            $id,
            $promotion['name_promotion'],
            $promotion['start_date'],
            $promotion['end_date'],
            $discount_type,
            $promotion['discount_value'],
            $promotion['description'],
            $promotion['status']
        );
        echo 'success';
        exit();
    }
}

// Xử lý xóa
if (isset($_GET['delete'])) {
    $model->deletePromotion($_GET['delete']);
    header("Location: ../admin/promotions.php");
    exit();
}

// Xử lý tìm kiếm
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$promotions = $model->getPromotions($search);

// Xử lý thêm khuyến mãi
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['name_promotion'], $_POST['start_date'], $_POST['end_date'], $_POST['discount_type'], $_POST['discount_value'], $_POST['description'], $_POST['status'])
) {
    $model->addPromotion(
        $_POST['name_promotion'],
        $_POST['start_date'],
        $_POST['end_date'],
        $_POST['discount_type'],
        $_POST['discount_value'],
        $_POST['description'],
        $_POST['status']
    );
    header('Location: ../admin/promotions.php');
    exit();
}
