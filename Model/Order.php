<?php
require_once __DIR__ . '/../config/config.php';

class Order
{
    // Tạo đơn hàng mới
    public function createOrder($userId, $address, $paymentMethod, $cartItems, $total)
    {
        global $conn;
        try {
            $conn->beginTransaction();

            // Lưu đơn hàng
            $stmt = $conn->prepare("INSERT INTO orders (user_id, address, payment_method, total, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$userId, $address, $paymentMethod, $total]);
            $orderId = $conn->lastInsertId();

            // Lưu từng sản phẩm trong đơn hàng
            $stmtItem = $conn->prepare("INSERT INTO order_items (order_id, product_name, variant_id, quantity, price) VALUES (?, ?, ?, ?, ?)");
            foreach ($cartItems as $item) {
                $stmtItem->execute([
                    $orderId,
                    $item['name_product'],
                    $item['id_variant'],
                    $item['quantity'],
                    $item['price']
                ]);
            }

            $conn->commit();
            return $orderId;
        } catch (Exception $e) {
            $conn->rollBack();
            error_log("Order creation failed: " . $e->getMessage());
            return false;
        }
    }

    // Lấy danh sách đơn hàng của user
    public function getOrdersByUser($userId)
    {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Lấy chi tiết từng đơn hàng
        foreach ($orders as &$order) {
            $stmtItems = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
            $stmtItems->execute([$order['id']]);
            $order['items'] = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
        }
        return $orders;
    }
} 