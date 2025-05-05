<?php
require_once('../../config/config.php'); // Đảm bảo đường dẫn đúng

class RoleDetailModel {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    // Lấy danh sách chi tiết role theo ID role
    public function getDetailsByRoleId($role_id) {
        $query = "SELECT * FROM chitietrole WHERE id_role = :role_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role_id', $role_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm chi tiết role mới
    public function addRoleDetail($role_id, $detail_name, $description) {
        $query = "INSERT INTO chitietrole (id_role, ten_chitietrole, mota) 
                  VALUES (:role_id, :ten_chitietrole, :mota)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role_id', $role_id, PDO::PARAM_INT);
        $stmt->bindParam(':ten_chitietrole', $detail_name, PDO::PARAM_STR);
        $stmt->bindParam(':mota', $description, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Cập nhật chi tiết role
    public function updateRoleDetail($detail_id, $detail_name, $description) {
        $query = "UPDATE chitietrole SET ten_chitietrole = :ten_chitietrole, mota = :mota WHERE id_chitietrole = :id_chitietrole";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_chitietrole', $detail_id, PDO::PARAM_INT);
        $stmt->bindParam(':ten_chitietrole', $detail_name, PDO::PARAM_STR);
        $stmt->bindParam(':mota', $description, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Xóa chi tiết role
    public function deleteRoleDetail($detail_id) {
        $query = "DELETE FROM chitietrole WHERE id_chitietrole = :id_chitietrole";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_chitietrole', $detail_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
