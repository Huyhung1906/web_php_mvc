<?php
session_start();
include '../../config/config.php';
include '../../model/adminmodel.php';

// Kiểm tra quyền truy cập, chỉ cho phép Admin truy cập
if (!isset($_SESSION['id_role']) || $_SESSION['id_role'] != 1) {
    header("Location: ../view/auth/login.php");
    exit();
}

$model = new AdminModel($conn);
$roles = $model->getAllRoles();
// Xử lý thêm người dùng
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];

    // Kiểm tra tính hợp lệ của dữ liệu đầu vào (bạn có thể thêm các kiểm tra khác)
    if (empty($username) || empty($password) || empty($fullname) || empty($email) || empty($phone) || empty($role)) {
        $error = "Vui lòng điền đầy đủ thông tin!";}
    if ($model->checkUsernameExists($username)) {
        $error = "Tên đăng nhập đã tồn tại!";
    } else {
        // Thêm người dùng vào cơ sở dữ liệu
        $model->addUser($username, $password, $fullname, $email, $phone, $role);
        header("Location: users.php");
        exit();
    }
}

?>
