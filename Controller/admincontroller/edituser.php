<?php
// Kiểm tra nếu session chưa được bắt đầu thì mới gọi session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../../config/config.php';
include '../../model/user.php';
include '../../model/adminmodel.php';

// Kiểm tra nếu có tham số ID người dùng
if (isset($_GET['id'])) {
    $id_user = $_GET['id'];

    // Khởi tạo đối tượng UserModel để lấy thông tin người dùng
    $userModel = new UserModel();
    $user = $userModel->getUserById2($id_user);

    if (!$user) {
        // Nếu không tìm thấy người dùng, chuyển hướng về danh sách người dùng hoặc hiển thị thông báo lỗi
        header('Location: users.php');
        exit();
    }

    // Lấy danh sách các vai trò (roles)
    $model = new AdminModel($conn);
    $roles = $model->getAllRoles();

    // Kiểm tra nếu có dữ liệu gửi lên từ form (POST)
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $role = $_POST['role'];

        // Cập nhật người dùng vào database
        $updateStatus = $userModel->updateUser($id_user, $username, $fullname, $email, $phone, $role);

        if ($updateStatus) {
            // Nếu cập nhật thành công, chuyển hướng về trang danh sách người dùng
            header('Location: users.php');
            exit();
        } else {
            // Nếu có lỗi khi cập nhật, hiển thị thông báo lỗi
            $error = "Cập nhật không thành công!";
        }
    }
} else {
    // Nếu không có ID người dùng, chuyển hướng về danh sách người dùng
    header('Location: users.php');
    exit();
}
?>
