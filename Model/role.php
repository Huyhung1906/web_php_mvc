<?php
require_once('../../config/config.php'); // Kết nối database

class RoleModel {
    private $conn;

    // Khởi tạo kết nối cơ sở dữ liệu
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Lấy tất cả các role
    public function getAll() {
        try {
            $stmt = $this->conn->query("SELECT * FROM role");
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Lỗi truy vấn: " . $e->getMessage();
            return false;
        }
    }
    

    // Lấy role theo id
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM role WHERE id_role = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tạo mới một role
    public function create($name, $desc) {
        $stmt = $this->conn->prepare("INSERT INTO role (role_name, description) VALUES (?, ?)");
        return $stmt->execute([$name, $desc]);
    }

    // Cập nhật role
    public function update($id, $name, $desc) {
        $stmt = $this->conn->prepare("UPDATE role SET role_name = ?, description = ? WHERE id_role = ?");
        return $stmt->execute([$name, $desc, $id]);
    }

    // Xóa role
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM role WHERE id_role = ?");
        return $stmt->execute([$id]);
    }
    public function hasPermissions($id_role, $permission_ids) {
        // Tạo câu lệnh SQL kiểm tra các quyền trong mảng $permission_ids
        $placeholders = implode(',', array_fill(0, count($permission_ids), '?'));

        $query = "SELECT COUNT(*) FROM phanrole 
                  WHERE id_role = :id_role AND id_chitietrole IN ($placeholders)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_role', $id_role);

        // Gắn giá trị các quyền vào câu lệnh
        foreach ($permission_ids as $index => $permission_id) {
            $stmt->bindValue($index + 1, $permission_id, PDO::PARAM_INT);
        }

        $stmt->execute();
        $count = $stmt->fetchColumn();

        return $count === count($permission_ids); // Nếu tất cả các quyền đều có, trả về true
    }
}
?>
