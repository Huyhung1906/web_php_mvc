<?php
session_start();
include '../../config/config.php';
include '../../model/adminmodel.php';

if (!isset($_SESSION['id_role']) || $_SESSION['id_role'] == 3) {
    header("Location: ../auth/login.php");
    exit();
}

$model = new AdminModel($conn);

$customers = $model->getCustomerCount();
$orders = $model->getOrderCount();
// Xử lý xóa
if (isset($_GET['delete'])) {
    $model->deleteUser($_GET['delete']);
    header("Location: users.php");
    exit();
}

// Xử lý tìm kiếm
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$users = $model->getUsers($search);
$revenue =$model->getTotalRevenue();
$top_products = $model->getTopProducts();

