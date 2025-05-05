<?php
session_start();
include '../../config/config.php';
include '../../model/role.php';

class RoleController {
    private $model;

    public function __construct($conn) {
        $this->model = new RoleModel($conn);
    }
    public function index() {
        $roles = $this->model->getAll();  // lấy dữ liệu từ model

        // truyền dữ liệu sang view qua biến $roles
        include('../../View/admin/role.php'); // View sẽ dùng được $roles
    }
    // Kiểm tra quyền truy cập
    public function checkAccess() {
        if (!isset($_SESSION['id_role']) || $_SESSION['id_role'] == 3) {
            header("Location: ../auth/login.php");
            exit();
        }
    }

    // Xử lý xóa role
    public function deleteRole() {
        if (isset($_GET['delete'])) {
            $roleId = $_GET['delete'];
            $this->model->delete($roleId);
            header("Location: roles.php");
            exit();
        }
    }
}

// Khởi tạo RoleController và gọi các phương thức tương ứng
$roleController = new RoleController($conn);
$roleController->checkAccess();  // Kiểm tra quyền truy cập
$roleController->deleteRole();  // Xử lý xóa role
$roles = $roleController->index();  // Lấy danh sách role
