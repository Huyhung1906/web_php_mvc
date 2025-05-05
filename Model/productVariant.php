<?php
class ProductVariantModel {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Lấy danh sách variants theo filter
    public function getFilteredVariants($sql, $params) {
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin variant theo id
    public function getVariantById($id) {
        $sql = "SELECT pv.*, p.name_product, sz.size_value, c.color_name FROM product_variant pv "
             . "LEFT JOIN product p ON pv.id_product = p.id_product "
             . "LEFT JOIN size sz ON pv.id_size = sz.id_size "
             . "LEFT JOIN color c ON pv.id_color = c.id_color "
             . "WHERE pv.id_variant = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm variant mới
    public function addVariant($data) {
        $sql = "INSERT INTO product_variant (id_product, id_size, id_color, quantity, expired_date) VALUES (:product, :size, :color, :quantity, :expired)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':product', $data['product']);
        $stmt->bindParam(':size', $data['size']);
        $stmt->bindParam(':color', $data['color']);
        $stmt->bindParam(':quantity', $data['quantity']);
        $stmt->bindParam(':expired', $data['expired']);
        $stmt->execute();
        return $this->conn->lastInsertId();
    }

    // Cập nhật variant
    public function updateVariant($id, $data) {
        $sql = "UPDATE product_variant SET id_product = :product, id_size = :size, id_color = :color, quantity = :quantity, expired_date = :expired WHERE id_variant = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':product', $data['product']);
        $stmt->bindParam(':size', $data['size']);
        $stmt->bindParam(':color', $data['color']);
        $stmt->bindParam(':quantity', $data['quantity']);
        $stmt->bindParam(':expired', $data['expired']);
        return $stmt->execute();
    }

    // Xóa variant
    public function deleteVariant($id) {
        $sql = "DELETE FROM product_variant WHERE id_variant = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Lấy danh sách sản phẩm
    public function getAllProducts() {
        $stmt = $this->conn->prepare("SELECT id_product, name_product FROM product");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách kích cỡ
    public function getAllSizes() {
        $stmt = $this->conn->prepare("SELECT id_size, size_value
         FROM size");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách màu sắc
    public function getAllColors() {
        $stmt = $this->conn->prepare("SELECT id_color, color_name FROM color");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
