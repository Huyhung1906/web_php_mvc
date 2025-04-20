<?php
require_once('../../config/config.php'); // Kết nối database

class UserModel {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Kiểm tra tài khoản đăng nhập
    public function checkUserCredentials($username, $password) {
        $stmt = $this->conn->prepare("SELECT id_user, username, password, id_role, is_active FROM user WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    // Kiểm tra username đã tồn tại hay chưa
    public function isUsernameExists($username) {
        $stmt = $this->conn->prepare("SELECT id_user FROM user WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Đăng ký tài khoản
    public function register($username, $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO user (username, password, id_role, is_active) VALUES (:username, :password, 2, 1)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        return $stmt->execute();
    }
    
    // Get user by ID
    public function getUserById($userId) {
        $stmt = $this->conn->prepare("SELECT id_user, username, id_role, is_active FROM user WHERE id_user = :id_user");
        $stmt->bindParam(':id_user', $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
