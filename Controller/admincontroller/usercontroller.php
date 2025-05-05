<?php
session_start();
include '../../config/config.php';
include '../../model/adminmodel.php';

if (!isset($_SESSION['id_role']) || $_SESSION['id_role'] != 1) {
    header("Location: ../view/auth/login.php");
    exit();
}



$model = new AdminModel($conn);
// Xử lý xóa
if (isset($_GET['delete'])) {
    $model->deleteUser($_GET['delete']);
    header("Location: users.php");
    exit();
}

// Xử lý tìm kiếm
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Lấy danh sách người dùng (có hoặc không có từ khóa tìm kiếm)
if ($search !== '') {
    $users = $model->getUsers($search); // nên trả về name_role trong kết quả
} else {
    $users = $model->getAllUsersWithRole();
}


