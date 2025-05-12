<?php
session_start();
include '../../config/config.php';
include '../../model/adminmodel.php';

if (!isset($_SESSION['id_role']) || $_SESSION['id_role'] == 3) {
    header("Location: ../auth/login.php");
    exit();
}



$model = new AdminModel($conn);
$check = new AdminModel($conn);
// Xử lý xóa
// Xử lý xóa người dùng
if (isset($_GET['delete'])) {
    $userId = $_GET['delete'];
    
    // Kiểm tra xem người dùng có quyền xóa người dùng hay không
    if ($model->canPerformAction($_SESSION['id_role'], 7)) { // Giả sử quyền xóa có id = 3
        $model->deleteUser($userId);
        header("Location: users.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Bạn không có quyền xóa người dùng.";
        header("Location: users.php");
        exit();
    }
}
// Xử lý tìm kiếm
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Lấy danh sách người dùng (có hoặc không có từ khóa tìm kiếm)
if ($search !== '') {
    $users = $model->getUsers($search); // nên trả về name_role trong kết quả
} else {
    $users = $model->getAllUsersWithRole();
}


