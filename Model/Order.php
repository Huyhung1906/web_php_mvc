<?php
require_once __DIR__ . '/../config/config.php';

class Order
{
    // Tạo hóa đơn mới
    public function createOrder($userId, $address, $paymentMethod, $cartItems, $total)
    {
        global $conn;
        try {
            $conn->beginTransaction();

            // Lưu hóa đơn
            $stmt = $conn->prepare("INSERT INTO invoice (id_user, CustomerAddress, Status, TotalAmount, InvoiceDate) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$userId, $address, 'Đang xử lý', $total]);
            $invoiceId = $conn->lastInsertId();

            // Lưu từng sản phẩm trong chi tiết hóa đơn
            $stmtItem = $conn->prepare("INSERT INTO invoicedetail (id_invoice, id_variant, quantity, sub_total) VALUES (?, ?, ?, ?)");
            foreach ($cartItems as $item) {
                $stmtItem->execute([
                    $invoiceId,
                    $item['id_variant'],
                    $item['quantity'],
                    $item['price'] * $item['quantity'] // sub_total = price * quantity
                ]);
            }

            $conn->commit();
            return $invoiceId;
        } catch (Exception $e) {
            $conn->rollBack();
            error_log("Invoice creation failed: " . $e->getMessage());
            return false;
        }
    }

    // Lấy danh sách hóa đơn của user
    public function getOrdersByUser($userId)
    {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM invoice WHERE id_user = ? ORDER BY InvoiceDate DESC");
        $stmt->execute([$userId]);
        $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Lấy chi tiết từng hóa đơn
        foreach ($invoices as &$invoice) {
            $stmtItems = $conn->prepare("SELECT * FROM invoicedetail WHERE id_invoice = ?");
            $stmtItems->execute([$invoice['id_invoice']]);
            $invoice['items'] = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
        }
        return $invoices;
    }
}
