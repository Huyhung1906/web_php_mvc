<?php
require_once('../../config/config.php');

class PromotionProductModel {
    private $conn;
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Thêm sản phẩm vào khuyến mãi
    public function addPromotionProduct($id_promotion, $id_product, $promotion_price) {
        $stmt = $this->conn->prepare("INSERT INTO promotions_product (id_promotion, id_product, promotion_price) VALUES (:id_promotion, :id_product, :promotion_price)");
        $stmt->bindParam(':id_promotion', $id_promotion);
        $stmt->bindParam(':id_product', $id_product);
        $stmt->bindParam(':promotion_price', $promotion_price);
        return $stmt->execute();
    }

    // Lấy danh sách sản phẩm theo khuyến mãi
    public function getProductsByPromotion($id_promotion) {
        $stmt = $this->conn->prepare("SELECT * FROM promotions_product WHERE id_promotion = :id_promotion");
        $stmt->bindParam(':id_promotion', $id_promotion);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Xóa sản phẩm khỏi khuyến mãi
    public function deletePromotionProduct($id_promotion, $id_product) {
        $stmt = $this->conn->prepare("DELETE FROM promotions_product WHERE id_promotion = :id_promotion AND id_product = :id_product");
        $stmt->bindParam(':id_promotion', $id_promotion);
        $stmt->bindParam(':id_product', $id_product);
        return $stmt->execute();
    }

    // Cập nhật giá khuyến mãi
    public function updatePromotionPrice($id_promotion, $id_product, $promotion_price) {
        $stmt = $this->conn->prepare("UPDATE promotions_product SET promotion_price = :promotion_price WHERE id_promotion = :id_promotion AND id_product = :id_product");
        $stmt->bindParam(':promotion_price', $promotion_price);
        $stmt->bindParam(':id_promotion', $id_promotion);
        $stmt->bindParam(':id_product', $id_product);
        return $stmt->execute();
    }

    // Lấy tất cả sản phẩm khuyến mãi
    public function getAllPromotionProducts() {
        $stmt = $this->conn->prepare("SELECT * FROM promotions_product");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updatePromotionForProduct($id_product, $id_promotion, $promotion_price) {
        $stmt = $this->conn->prepare("UPDATE promotions_product SET id_promotion = :id_promotion, promotion_price = :promotion_price WHERE id_product = :id_product");
        $stmt->bindParam(':id_promotion', $id_promotion);
        $stmt->bindParam(':promotion_price', $promotion_price);
        $stmt->bindParam(':id_product', $id_product);
        return $stmt->execute();
    }
} 