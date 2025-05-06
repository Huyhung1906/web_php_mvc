<?php
require_once('../../config/config.php'); // Kết nối database

class PromotionModel {
    private $conn;
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Lấy danh sách khuyến mãi
    public function getPromotions($search = '') {
        try {
            if ($search !== '') {
                $stmt = $this->conn->prepare(
                    "SELECT * FROM promotions 
                     WHERE id_promotions LIKE :search 
                     OR name_promotion LIKE :search"
                );
                $likeSearch = "%$search%";
                $stmt->bindParam(':search', $likeSearch);
            } else {
                $stmt = $this->conn->prepare("SELECT * FROM promotions");
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting promotions: " . $e->getMessage());
            return [];
        }
    }

    // Lấy thông tin khuyến mãi theo ID
    public function getPromotionById($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM promotions WHERE id_promotions = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting promotion by ID: " . $e->getMessage());
            return null;
        }
    }

    // Thêm khuyến mãi
    public function addPromotion($name_promotion, $start_date, $end_date, $discount_type, $discount_value, $description, $status) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO promotions (name_promotion, start_date, end_date, discount_type, discount_value, description, status) VALUES (:name_promotion, :start_date, :end_date, :discount_type, :discount_value, :description, :status)");
            $stmt->bindParam(':name_promotion', $name_promotion);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':end_date', $end_date);
            $stmt->bindParam(':discount_type', $discount_type);
            $stmt->bindParam(':discount_value', $discount_value);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':status', $status);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error adding promotion: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật khuyến mãi
    public function updatePromotion($id, $name_promotion, $start_date, $end_date, $discount_type, $discount_value, $description, $status) {
        try {
            $stmt = $this->conn->prepare("UPDATE promotions SET name_promotion = :name_promotion, start_date = :start_date, end_date = :end_date, discount_type = :discount_type, discount_value = :discount_value, description = :description, status = :status WHERE id_promotions = :id");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name_promotion', $name_promotion);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':end_date', $end_date);
            $stmt->bindParam(':discount_type', $discount_type);
            $stmt->bindParam(':discount_value', $discount_value);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':status', $status);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error updating promotion: " . $e->getMessage());
            return false;
        }
    }

    // Xóa khuyến mãi
    public function deletePromotion($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM promotions WHERE id_promotions = :id");
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error deleting promotion: " . $e->getMessage());
            return false;
        }
    }

    // Thêm sản phẩm vào khuyến mãi
    public function addPromotionProduct($id_promotion, $id_product, $promotion_price) {
        $stmt = $this->conn->prepare("INSERT INTO promotion_products (id_promotion, id_product, promotion_price) VALUES (:id_promotion, :id_product, :promotion_price)");
        $stmt->bindParam(':id_promotion', $id_promotion);
        $stmt->bindParam(':id_product', $id_product);
        $stmt->bindParam(':promotion_price', $promotion_price);
        return $stmt->execute();
    }

    // Cập nhật trạng thái khuyến mãi
    public function updatePromotionStatus($id, $status) {
        try {
            $stmt = $this->conn->prepare("UPDATE promotions SET status = :status WHERE id_promotions = :id");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':status', $status);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error updating promotion status: " . $e->getMessage());
            return false;
        }
    }
}
