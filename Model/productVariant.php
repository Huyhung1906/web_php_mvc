<?php
require_once(__DIR__ . '/../config/config.php');

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

    // Kiểm tra biến thể đã tồn tại chưa
    public function checkVariantExists($productId, $sizeId, $colorId) {
        $sql = "SELECT COUNT(*) FROM product_variant 
                WHERE id_product = :product 
                AND id_size = :size 
                AND id_color = :color";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':product', $productId);
        $stmt->bindParam(':size', $sizeId);
        $stmt->bindParam(':color', $colorId);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
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
        return $stmt->execute([':id' => $id]);
    }

    // Lấy danh sách sản phẩm
    public function getAllProducts() {
        $stmt = $this->conn->prepare("SELECT id_product, name_product FROM product");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách kích cỡ
    public function getAllSizes() {
        $stmt = $this->conn->prepare("SELECT id_size, size_value FROM size");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách màu sắc
    public function getAllColors() {
        $stmt = $this->conn->prepare("SELECT id_color, color_name FROM color");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách biến thể với filter
    public function getVariants($product = '', $size = '', $color = '', $search = '') {
        $sql = "SELECT pv.id_variant, p.name_product, sz.size_value, c.color_name, pv.quantity, pv.expired_date
                FROM product_variant pv
                INNER JOIN product p ON pv.id_product = p.id_product
                INNER JOIN size sz ON pv.id_size = sz.id_size
                INNER JOIN color c ON pv.id_color = c.id_color
                WHERE 1=1";
        $params = [];
        if ($product) {
            $sql .= " AND pv.id_product = :product";
            $params[':product'] = $product;
        }
        if ($size) {
            $sql .= " AND pv.id_size = :size";
            $params[':size'] = $size;
        }
        if ($color) {
            $sql .= " AND pv.id_color = :color";
            $params[':color'] = $color;
        }
        if ($search) {
            $sql .= " AND p.name_product LIKE :search";
            $params[':search'] = "%$search%";
        }
        $sql .= " ORDER BY pv.id_variant ASC";
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
