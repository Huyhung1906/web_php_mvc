<?php
require_once('../../config/config.php'); // Kết nối database
require_once __DIR__ . '/../Model/Order.php';

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
    public function updateUser($id, $username, $fullname, $email, $phone, $role) {
        $sql = "UPDATE user SET username = ?, fullname = ?, email = ?, phone = ?, id_role = ? WHERE id_user = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$username, $fullname, $email, $phone, $role, $id]);
    }
    
    // Get user addresses
    public function getUserAddresses($userId) {
        try {
            $stmt = $this->conn->prepare("SELECT id_address, province, district, ward, street, address_type FROM address WHERE id_user = :id_user");
            $stmt->bindParam(':id_user', $userId);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting user addresses: " . $e->getMessage());
            return [];
        }
    }
    // Get user by ID
    public function getUserById2($userId) {
        $stmt = $this->conn->prepare("SELECT id_user, username,fullname,email,phone, id_role, is_active FROM user WHERE id_user = :id_user");
        $stmt->bindParam(':id_user', $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function addAddress($userId, $province, $district, $ward, $street, $addressType) {
        try {
            // Check if address already exists for this user
            $checkStmt = $this->conn->prepare("SELECT id_address FROM address WHERE id_user = :id_user");
            $checkStmt->bindParam(':id_user', $userId);
            $checkStmt->execute();
            
            if ($checkStmt->rowCount() > 0) {
                // Address exists, update it
                $addressId = $checkStmt->fetch(PDO::FETCH_ASSOC)['id_address'];
                $stmt = $this->conn->prepare("UPDATE address 
                                            SET province = :province, 
                                                district = :district, 
                                                ward = :ward, 
                                                street = :street, 
                                                address_type = :address_type 
                                            WHERE id_address = :id_address");
                $stmt->bindParam(':province', $province);
                $stmt->bindParam(':district', $district);
                $stmt->bindParam(':ward', $ward);
                $stmt->bindParam(':street', $street);
                $stmt->bindParam(':address_type', $addressType);
                $stmt->bindParam(':id_address', $addressId);
            } else {
                // No address exists, insert new one
                $stmt = $this->conn->prepare("INSERT INTO address (id_user, province, district, ward, street, address_type, created_date) 
                                            VALUES (:id_user, :province, :district, :ward, :street, :address_type, NOW())");
                $stmt->bindParam(':id_user', $userId);
                $stmt->bindParam(':province', $province);
                $stmt->bindParam(':district', $district);
                $stmt->bindParam(':ward', $ward);
                $stmt->bindParam(':street', $street);
                $stmt->bindParam(':address_type', $addressType);
            }
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error adding/updating address: " . $e->getMessage());
            return false;
        }
    }
}
