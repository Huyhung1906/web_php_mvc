<?php
session_start();
include '../../config/config.php';
include '../../model/role.php';
include '../../model/adminmodel.php';
class RoleController
{
    private $model;

    public function __construct($conn)
    {
        $this->model = new RoleModel($conn);
    }

    // Kiểm tra quyền truy cập
    public function checkAccess()
    {
        if (!isset($_SESSION['id_role']) || $_SESSION['id_role'] == 3) {
            header("Location: ../auth/login.php");
            exit();
        }
    }

    // Xử lý thêm mới role
    public function createRole()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_role'])) {
            $name = trim($_POST['name_role']);
            $desc = trim($_POST['description']);

            if (!empty($name) && !empty($desc)) {
                $this->model->create($name, $desc);
                header("Location: role.php"); // quay lại trang role sau khi thêm
                exit();
            } else {
                // có thể xử lý lỗi nếu cần
                $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin.";
            }
        }
    }

    // Hiển thị chi tiết role và phân quyền
    public function showRoleDetail()
    {
        $roles = $this->model->getAll();
        $assignedPermissions = [];
        $selectedRole = null;

        // Nếu có role_id được chọn để xem phân quyền, nếu không thì mặc định là id = 1
        $roleId = isset($_GET['id']) ? $_GET['id'] : 2;  // Nếu không có id trong URL thì mặc định id = 1

        $assignedPermissions = $this->model->getAssignedPermissions($roleId);
        $selectedRole = $this->model->getById($roleId);
        $check = new AdminModel($GLOBALS['conn']);
        // Kiểm tra kết quả trả về từ getAssignedPermissio
        // Truyền dữ liệu vào view
        include('../../View/admin/role.php');
    }
    // Lưu phân quyền cho Role
    public function savePermissions() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_permissions'])) {
        $id_role = $_POST['id_role'];
        $permissions = isset($_POST['permissions']) ? $_POST['permissions'] : [];

        // Xoá tất cả quyền cũ của role
        $this->model->deletePermissionsByRole($id_role);

        // Gán lại quyền mới
        foreach ($permissions as $permission_id) {
            $this->model->assignPermission($id_role, $permission_id);
        }

        // Lưu thông báo thành công vào session
        $_SESSION['message'] = "Quyền đã được cập nhật thành công!";
        
        // Chuyển hướng lại để tránh gửi lại form
        header("Location: role.php?id=" . $id_role);
        exit();
    }
}





    // Xử lý xóa role
    public function deleteRole()
    {
        if (isset($_GET['delete'])) {
            $roleId = $_GET['delete'];
            $this->model->delete($roleId);
            header("Location: role.php");
            exit();
        }
    }

    // Hiển thị danh sách role
}

// Khởi tạo controller và xử lý
$roleController = new RoleController($conn);
if (isset($_GET['id'])) {
    $roleController->savePermissions();  // ✅ xử lý lưu quyền
    $roleController->showRoleDetail();// Hiển thị chi tiết nếu có ID
} else {
    $roleController->checkAccess();
    $roleController->createRole();  // Xử lý thêm mới nếu có
    $roleController->deleteRole();  // Xử lý xóa nếu có    // Hiển thị danh sách role
}
