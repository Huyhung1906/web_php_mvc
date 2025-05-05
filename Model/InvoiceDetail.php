<?php
require_once('../../config/config.php');

class InvoiceDetailModel {
    private $conn;
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Lấy danh sách sản phẩm trong hóa đơn
    public function getInvoiceDetails($invoiceId) {
        $stmt = $this->conn->prepare("SELECT * FROM invoicedetail WHERE id_invoice = :id_invoice");
        $stmt->bindParam(':id_invoice', $invoiceId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm sản phẩm vào hóa đơn
    public function addInvoiceDetail($invoiceId, $variantId, $quantity, $subTotal) {
        try {
            // Bắt đầu transaction
            $this->conn->beginTransaction();

            // Thêm chi tiết hóa đơn
            $stmt = $this->conn->prepare("INSERT INTO invoicedetail (id_invoice, id_variant, quantity, sub_total) VALUES (:id_invoice, :id_variant, :quantity, :sub_total)");
            $stmt->bindParam(':id_invoice', $invoiceId);
            $stmt->bindParam(':id_variant', $variantId);
            $stmt->bindParam(':quantity', $quantity);
            $stmt->bindParam(':sub_total', $subTotal);
            $result = $stmt->execute();

            if ($result) {
                // Trừ số lượng trong product_variant
                $updateStmt = $this->conn->prepare("UPDATE product_variant SET quantity = quantity - :quantity WHERE id_variant = :id_variant AND quantity >= :quantity");
                $updateStmt->bindParam(':quantity', $quantity);
                $updateStmt->bindParam(':id_variant', $variantId);
                $updateResult = $updateStmt->execute();

                if ($updateResult) {
                    $this->conn->commit();
                    return true;
                } else {
                    $this->conn->rollBack();
                    return false;
                }
            } else {
                $this->conn->rollBack();
                return false;
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error in addInvoiceDetail: " . $e->getMessage());
            return false;
        }
    }

    // Xóa sản phẩm khỏi hóa đơn
    public function deleteInvoiceDetail($invoiceId, $variantId) {
        $stmt = $this->conn->prepare("DELETE FROM invoicedetail WHERE id_invoice = :id_invoice AND id_variant = :id_variant");
        $stmt->bindParam(':id_invoice', $invoiceId);
        $stmt->bindParam(':id_variant', $variantId);
        return $stmt->execute();
    }

    // (Tùy chọn) Sửa sản phẩm trong hóa đơn
    public function updateInvoiceProduct($invoiceId, $variantId, $quantity, $subTotal) {
        $stmt = $this->conn->prepare("UPDATE invoicedetail SET quantity = :quantity, sub_total = :sub_total WHERE id_invoice = :id_invoice AND id_variant = :id_variant");
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':sub_total', $subTotal);
        $stmt->bindParam(':id_invoice', $invoiceId);
        $stmt->bindParam(':id_variant', $variantId);
        return $stmt->execute();
    }

    public function getAllVariants($invoiceId) {
        $stmt = $this->conn->prepare("
            SELECT pv.id_variant, p.name_product, sz.size_value, c.color_name 
            FROM product_variant pv 
            JOIN product p ON pv.id_product = p.id_product
            JOIN size sz ON pv.id_size = sz.id_size
            JOIN color c ON pv.id_color = c.id_color
            WHERE pv.quantity > 0
            AND pv.id_variant NOT IN (
                SELECT id_variant FROM invoicedetail WHERE id_invoice = :id_invoice
            )
        ");
        $stmt->bindParam(':id_invoice', $invoiceId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPriceByVariant($id_variant) {
        require('../../config/config.php');
        // Lấy id_product từ variant
        $stmt = $this->conn->prepare("SELECT id_product FROM product_variant WHERE id_variant = :id_variant");
        $stmt->bindParam(':id_variant', $id_variant);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return 0;

        $id_product = $row['id_product'];

        // Kiểm tra có khuyến mãi không
        $stmt = $this->conn->prepare("SELECT promotion_price FROM promotions_product WHERE id_product = :id_product");
        $stmt->bindParam(':id_product', $id_product);
        $stmt->execute();
        $promo = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($promo && $promo['promotion_price'] > 0) {
            return $promo['promotion_price'];
        }

        // Nếu không có khuyến mãi, lấy giá gốc
        $stmt = $this->conn->prepare("SELECT price FROM product WHERE id_product = :id_product");
        $stmt->bindParam(':id_product', $id_product);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product && $product['price'] > 0) {
            return $product['price'];
        }

        return 0;
    }

    public function updateInvoiceTotalAmount($invoiceId) {
        // Tính tổng sub_total
        $stmt = $this->conn->prepare("SELECT SUM(sub_total) as total FROM invoicedetail WHERE id_invoice = :id_invoice");
        $stmt->bindParam(':id_invoice', $invoiceId);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $total = $row ? $row['total'] : 0;

        // Cập nhật vào bảng invoice
        $stmt = $this->conn->prepare("UPDATE invoice SET TotalAmount = :total WHERE id_invoice = :id_invoice");
        $stmt->bindParam(':total', $total);
        $stmt->bindParam(':id_invoice', $invoiceId);
        return $stmt->execute();
    }

    public function getVariantInfo($variantId) {
        $stmt = $this->conn->prepare("
            SELECT p.name_product, sz.size_value, c.color_name 
            FROM product_variant pv 
            JOIN product p ON pv.id_product = p.id_product
            JOIN size sz ON pv.id_size = sz.id_size
            JOIN color c ON pv.id_color = c.id_color
            WHERE pv.id_variant = :id_variant
        ");
        $stmt->bindParam(':id_variant', $variantId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
