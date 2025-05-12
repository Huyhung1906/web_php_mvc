<?php
class AdminProductModel {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Lấy danh sách sản phẩm với bộ lọc
    public function getFilteredProducts($sql, $params) {
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách sản phẩm
    public function getAllProducts() {
        $sql = "SELECT p.*, b.name_brand, l.name_category, c.name_category as category_name 
                FROM product p 
                LEFT JOIN brand b ON p.id_brand = b.id_brand 
                LEFT JOIN line l ON p.id_line = l.id_line 
                LEFT JOIN category c ON l.id_category = c.id_category";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin chi tiết sản phẩm
    public function getProductById($id) {
        $sql = "SELECT p.*, b.name_brand, l.name_category, c.name_category as category_name 
                FROM product p 
                LEFT JOIN brand b ON p.id_brand = b.id_brand 
                LEFT JOIN line l ON p.id_line = l.id_line 
                LEFT JOIN category c ON l.id_category = c.id_category 
                WHERE p.id_product = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách hình ảnh của sản phẩm
    public function getProductImages($productId) {
        $sql = "SELECT * FROM image WHERE id_product = :productId";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':productId', $productId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm sản phẩm mới
    public function addProduct($data) {
        $sql = "INSERT INTO product (name_product, id_line, id_brand, description, material, price, imageUrl, releasedate, status) 
                VALUES (:name, :line, :brand, :description, :material, :price, :image, :release, :status)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':line', $data['line']);
        $stmt->bindParam(':brand', $data['brand']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':material', $data['material']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':image', $data['image']);
        $stmt->bindParam(':release', $data['release']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->execute();
        return $this->conn->lastInsertId();
    }

    // Cập nhật sản phẩm
    public function updateProduct($id, $data) {
        $sql = "UPDATE product SET 
                name_product = :name,
                id_line = :line,
                id_brand = :brand,
                description = :description,
                material = :material,
                price = :price,
                imageUrl = :image,
                releasedate = :release,
                status = :status
                WHERE id_product = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':line', $data['line']);
        $stmt->bindParam(':brand', $data['brand']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':material', $data['material']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':image', $data['image']);
        $stmt->bindParam(':release', $data['release']);
        $stmt->bindParam(':status', $data['status']);
        return $stmt->execute();
    }

    // Xóa sản phẩm
    public function deleteProduct($id) {
        // Kiểm tra xem sản phẩm đã được bán chưa
        $sql = "SELECT COUNT(*) as count FROM invoicedetail id 
                JOIN product_variant pv ON id.id_variant = pv.id_variant 
                WHERE pv.id_product = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] > 0) {
            // Nếu đã bán, cập nhật trạng thái thành ẩn
            $sql = "UPDATE product SET status = 'Hidden' WHERE id_product = :id";
        } else {
            // Nếu chưa bán, xóa sản phẩm
            $sql = "DELETE FROM product WHERE id_product = :id";
        }
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Lấy danh sách thương hiệu
    public function getAllBrands() {
        try {
            error_log('Getting all brands...');
            $sql = "SELECT * FROM brand";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log('Brands query result: ' . print_r($result, true));
            
            if ($stmt->rowCount() === 0) {
                error_log('No brands found in database');
                throw new Exception("Không tìm thấy dữ liệu thương hiệu trong database");
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log('Database error in getAllBrands: ' . $e->getMessage());
            throw new Exception("Lỗi database khi lấy danh sách thương hiệu: " . $e->getMessage());
        }
    }

    // Lấy danh sách danh mục
    public function getAllCategories() {
        try {
            error_log('Getting all categories...');
            $sql = "SELECT * FROM category";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log('Categories query result: ' . print_r($result, true));
            
            if ($stmt->rowCount() === 0) {
                error_log('No categories found in database');
                throw new Exception("Không tìm thấy dữ liệu danh mục trong database");
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log('Database error in getAllCategories: ' . $e->getMessage());
            throw new Exception("Lỗi database khi lấy danh sách danh mục: " . $e->getMessage());
        }
    }

    // Lấy danh sách dòng sản phẩm
    public function getAllLines() {
        $sql = "SELECT l.*, l.name_category as line_name, c.name_category as category_name 
                FROM line l 
                JOIN category c ON l.id_category = c.id_category";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm hình ảnh sản phẩm
    public function addProductImage($data) {
        $sql = "INSERT INTO image (imageUrl, isPrimary, id_product, id_variant, id_color) 
                VALUES (:imageUrl, :isPrimary, :productId, :variantId, :colorId)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':imageUrl', $data['imageUrl']);
        $stmt->bindParam(':isPrimary', $data['isPrimary']);
        $stmt->bindParam(':productId', $data['productId']);
        $stmt->bindParam(':variantId', $data['variantId']);
        $stmt->bindParam(':colorId', $data['colorId']);
        return $stmt->execute();
    }

    // Xóa hình ảnh sản phẩm
    public function deleteProductImage($imageId) {
        $sql = "DELETE FROM image WHERE id_image = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $imageId);
        return $stmt->execute();
    }

    public function canPerformAction($id_role, $permission_id) {
        $stmt = $this->conn->prepare("SELECT * FROM phanrole WHERE id_role = :id_role AND id_chitietrole = :permission_id");
        $stmt->bindParam(':id_role', $id_role);
        $stmt->bindParam(':permission_id', $permission_id);
        $stmt->execute();
    
        return $stmt->rowCount() > 0;
    }

    public function getAllSizes() {
        try {
            $sql = "SELECT * FROM size ORDER BY size_value";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getAllSizes: " . $e->getMessage());
            return [];
        }
    }

    public function getAllColors() {
        try {
            $sql = "SELECT * FROM color ORDER BY color_name";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getAllColors: " . $e->getMessage());
            return [];
        }
    }
}
?>
