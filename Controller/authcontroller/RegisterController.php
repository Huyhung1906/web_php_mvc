<?php
session_start();
require_once('../../Model/user.php');

class RegisterController {
    public function handleRegister() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $confirm_password = trim($_POST['confirm_password'] ?? '');

            if (!empty($username) && !empty($password) && !empty($confirm_password)) {
                if ($password !== $confirm_password) {
                    $_SESSION['error_msg'] = "Mật khẩu xác nhận không khớp!";
                } else {
                    $user = new UserModel();
                    if ($user->isUsernameExists($username)) {
                        $_SESSION['error_msg'] = "Tên đăng nhập đã tồn tại!";
                    } else {
                        if ($user->register($username, $password)) {
                            $_SESSION['success_msg'] = "Đăng ký thành công! Bạn có thể đăng nhập ngay.";
                            header("Location: login.php");
                            exit();
                        } else {
                            $_SESSION['error_msg'] = "Có lỗi xảy ra, vui lòng thử lại!";
                        }
                    }
                }
            } else {
                $_SESSION['error_msg'] = "Vui lòng nhập đầy đủ thông tin!";
            }
        }
    }
}
?>
