<?php
session_start();
require_once('../../Model/user.php');

class LoginController {
    public function login() {
        // Khởi tạo thông báo lỗi
        $error_msg = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = isset($_POST['username']) ? trim($_POST['username']) : '';
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';

            // Kiểm tra thông tin đăng nhập
            if (!empty($username) && !empty($password)) {
                $userModel = new UserModel();
                $user = $userModel->checkUserCredentials($username, $password);

                if ($user) {
                    // Kiểm tra trạng thái tài khoản
                    if ($user['is_active'] == 0) {
                        $_SESSION['error_msg'] = "Tài khoản của bạn đã bị khóa!";
                    } else {
                        $_SESSION['username'] = $user['username']; 
                        $_SESSION['id_role'] = $user['id_role'];
                        $_SESSION['id_user'] = $user['id_user'];
                        header("Location: ../user/index.php");    
                        exit();
                    }
                } else {
                    $_SESSION['error_msg'] = "Sai tài khoản hoặc mật khẩu!";
                }
            } else {
                $_SESSION['error_msg'] = "Vui lòng nhập đầy đủ thông tin!";
            }
        }
    }
}
?>
