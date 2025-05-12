<?php
session_start();
include '../../config/config.php';
include '../../Model/StatisticsModel.php';

if (!isset($_SESSION['id_role']) || $_SESSION['id_role'] == 3) {
    header("Location: ../auth/login.php");
    exit();
}

$model = new StatisticsModel($conn);

// Xử lý form thống kê
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');
$orderBy = isset($_GET['order_by']) ? $_GET['order_by'] : 'DESC';

$topCustomers = $model->getTopCustomersByPurchaseAmount($startDate, $endDate, 5, $orderBy);

$customerOrders = [];
foreach ($topCustomers as $customer) {
    $customerOrders[$customer['id_user']] = $model->getCustomerOrders(
        $customer['id_user'],
        $startDate,
        $endDate
    );
}