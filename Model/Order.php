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
            
            // Log information for debugging
            error_log("Creating order for user ID: $userId with total: $total");
            error_log("Payment method: $paymentMethod, Address: $address");
            error_log("Cart items count: " . count($cartItems));

            // Lưu hóa đơn
            $stmt = $conn->prepare("INSERT INTO invoice (id_user, CustomerAddress, Status, TotalAmount, InvoiceDate) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$userId, $address, 'Đang xử lý', $total]);
            $invoiceId = $conn->lastInsertId();
            
            error_log("Created invoice with ID: $invoiceId");

            // Lưu từng sản phẩm trong chi tiết hóa đơn
            $stmtItem = $conn->prepare("INSERT INTO invoicedetail (id_invoice, id_variant, quantity, sub_total) VALUES (?, ?, ?, ?)");
            foreach ($cartItems as $item) {
                $subTotal = $item['price'] * $item['quantity'];
                $variantId = $item['id_variant'];
                $quantity = $item['quantity'];
                
                error_log("Adding item to invoice: Variant ID: $variantId, Quantity: $quantity, Subtotal: $subTotal");
                
                $stmtItem->execute([
                    $invoiceId,
                    $variantId,
                    $quantity,
                    $subTotal // sub_total = price * quantity
                ]);
            }

            $conn->commit();
            error_log("Order creation completed successfully");
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
    
    
    public function getUserOrders($userId) {
        global $conn;
        $orders = [];
        
        // In ra log để debug
        error_log("Getting orders for user ID: $userId");
        
        try {
            // Get all orders for this user (using invoice table)
            $stmt = $conn->prepare("
                SELECT i.*, 
                       DATE_FORMAT(i.InvoiceDate, '%Y-%m-%d %H:%i:%s') as created_at,
                       i.id_invoice as id_order,
                       i.CustomerAddress as shipping_address,
                       i.Status,
                       CASE 
                           WHEN i.Status = 'Cancelled' THEN 'Cancelled'
                           WHEN i.Status = 'Pending' THEN 'Pending'
                           ELSE 'Completed'
                       END as status,
                       i.TotalAmount as total_amount
                FROM invoice i
                WHERE i.id_user = ?
                ORDER BY i.InvoiceDate DESC
            ");
            $stmt->execute([$userId]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("Found " . count($results) . " orders for user");
            
            if (empty($results)) {
                return $orders;
            }
            
            // Get order items for each order (using invoicedetail table)
            foreach ($results as $order) {
                $orderId = $order['id_invoice'];
                error_log("Processing order ID: $orderId, Status: " . $order['Status']);
                
                try {
                    $stmtItems = $conn->prepare("
                        SELECT id.*, 
                               p.name_product,
                               p.imageUrl,
                               s.value as size_value,
                               c.name as color_name,
                               p.price,
                               (id.quantity * id.sub_total) as total_price
                        FROM invoicedetail id
                        JOIN product_variant pv ON id.id_variant = pv.id_variant
                        JOIN products p ON pv.id_product = p.id_product
                        LEFT JOIN sizes s ON pv.id_size = s.id_size
                        LEFT JOIN colors c ON pv.id_color = c.id_color
                        WHERE id.id_invoice = ?
                    ");
                    $stmtItems->execute([$orderId]);
                    $orderItems = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
                    
                    error_log("Found " . count($orderItems) . " items for order $orderId");
                    
                    $order['items'] = $orderItems;
                    $orders[] = $order;
                } catch (Exception $e) {
                    error_log("Error fetching order items: " . $e->getMessage());
                    // Vẫn thêm đơn hàng ngay cả khi không lấy được chi tiết
                    $order['items'] = [];
                    $orders[] = $order;
                }
            }
            
            return $orders;
        } catch (Exception $e) {
            error_log("Error in getUserOrders: " . $e->getMessage());
            return [];
        }
    }
    
    
    public function getMostRecentOrder($userId) {
        global $conn;
        $stmt = $conn->prepare("
            SELECT * FROM invoice
            WHERE id_user = ?
            ORDER BY InvoiceDate DESC
            LIMIT 1
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

    public function deleteOrder($orderId) {
        global $conn;
        try {
            $conn->beginTransaction();
            
            // Delete order items first (invoicedetail)
            $stmt = $conn->prepare("DELETE FROM invoicedetail WHERE id_invoice = ?");
            $stmt->execute([$orderId]);
            
            // Then delete the order (invoice)
            $stmt = $conn->prepare("DELETE FROM invoice WHERE id_invoice = ?");
            $stmt->execute([$orderId]);
            
            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollBack();
            error_log("Error deleting order: " . $e->getMessage());
            return false;
        }
    }
    

    /**
     * Cancel the most recent order for a user
     * 
     * @param int $userId The user ID
     * @return bool Success status
     */
    public function cancelMostRecentOrder($userId) {
        $order = $this->getMostRecentOrder($userId);
        
        if (!$order) {
            error_log("No recent order found for user ID: $userId");
            return false;
        }
        
        // Thay vì xóa đơn hàng, cập nhật trạng thái thành "Cancelled"
        try {
            global $conn;
            $stmt = $conn->prepare("UPDATE invoice SET Status = 'Cancelled' WHERE id_invoice = ?");
            $result = $stmt->execute([$order['id_invoice']]);
            
            if ($result) {
                error_log("Order ID: " . $order['id_invoice'] . " has been cancelled successfully");
            } else {
                error_log("Failed to cancel order ID: " . $order['id_invoice']);
            }
            
            return $result;
        } catch (Exception $e) {
            error_log("Error cancelling order: " . $e->getMessage());
            return false;
        }
    }
}
