<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../Model/Cart.php';
require_once __DIR__ . '/../Model/Product.php';
require_once __DIR__ . '/../Model/Order.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class CartController {
    private $cartModel;
    private $productModel;
    
    public function __construct() {
        $this->cartModel = new Cart();
        $this->productModel = new Product();
        
        // Initialize session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Add a product to the cart
     * 
     * @param int $productId Product ID
     * @param int $sizeId Size ID
     * @param int $quantity Quantity to add
     * @return array Response with status and message
     */
    public function addToCart() {
        // Check if user is logged in
        if (!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])) {
            return [
                'status' => 'error',
                'message' => 'You must be logged in to add items to the cart'
            ];
        }
        
        // Get parameters from POST request
        $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $sizeId = isset($_POST['size_id']) ? intval($_POST['size_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        
        error_log("Add to cart request - Product ID: $productId, Size ID: $sizeId, Quantity: $quantity");
        
        // Validate input
        if ($productId <= 0) {
            return [
                'status' => 'error',
                'message' => 'Invalid product'
            ];
        }
        
        if ($sizeId <= 0) {
            return [
                'status' => 'error',
                'message' => 'Please select a size'
            ];
        }
        
        if ($quantity <= 0) {
            $quantity = 1;
        }
        
        // Get the product variant ID based on product and size
        $variant = $this->getVariantIdByProductAndSize($productId, $sizeId);
        
        error_log("Variant data: " . print_r($variant, true));
        
        if (!$variant) {
            return [
                'status' => 'error',
                'message' => 'This product is not available in the selected size'
            ];
        }
        
        // Add to cart
        $userId = $_SESSION['id_user'];
        error_log("Adding to cart with User ID: $userId");
        $result = $this->cartModel->addToCart($userId, $variant['id_variant'], $quantity);
        
        if ($result) {
            // Get updated cart count
            $cartCount = $this->cartModel->getCartCount($userId);
            
            return [
                'status' => 'success',
                'message' => 'Product added to cart successfully',
                'cart_count' => $cartCount
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Failed to add product to cart'
            ];
        }
    }
    
    /**
     * Get the variant ID for a product and size
     * 
     * @param int $productId Product ID
     * @param int $sizeId Size ID
     * @return array|bool Variant data or false if not found
     */
    private function getVariantIdByProductAndSize($productId, $sizeId) {
        // This is a simplified implementation - you might need to adjust this
        // based on your actual database schema and relationships
        
        try {
            global $conn;
            
            $query = "SELECT pv.id_variant, pv.id_product, pv.id_color, pv.quantity
                     FROM product_variant pv
                     WHERE pv.id_product = :product_id AND pv.id_size = :size_id
                     LIMIT 1";
            
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
            $stmt->bindParam(':size_id', $sizeId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database Error in getVariantIdByProductAndSize: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * View cart page
     */
    public function viewCart() {
        // Check if user is logged in
        if (!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])) {
            header("Location: /web_php_mvc/login.php");
            exit;
        }
        
        $userId = $_SESSION['id_user'];
        
        // Get cart items with promotions applied
        $cartItems = $this->cartModel->applyPromotions($userId);
        
        // Calculate cart totals
        $subTotal = 0;
        $discount = 0;
        $total = 0;
        
        foreach ($cartItems as $item) {
            $subTotal += isset($item['original_price']) ? ($item['original_price'] * $item['quantity']) : $item['total_price'];
            
            // Calculate discount if promotion was applied
            if (isset($item['discount'])) {
                $discount += ($item['discount'] * $item['quantity']);
            }
            
            $total += $item['total_price'];
        }
        
        // Load the cart view with data
        include_once 'View/user/cart.php';
    }
    
    /**
     * Update cart item quantity
     */
    public function updateCartItemQuantity() {
        // Check if user is logged in
        if (!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'You must be logged in to update your cart'
            ]);
            return;
        }
        
        $userId = $_SESSION['id_user'];
        $variantId = isset($_POST['variant_id']) ? intval($_POST['variant_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
        
        if ($variantId <= 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid product variant'
            ]);
            return;
        }
        
        $result = $this->cartModel->updateCartItemQuantity($userId, $variantId, $quantity);
        
        if ($result) {
            $cartItems = $this->cartModel->getCart($userId);
            $cartCount = $this->cartModel->getCartCount($userId);
            
            // Calculate new totals
            $subTotal = 0;
            foreach ($cartItems as $item) {
                $subTotal += $item['total_price'];
            }
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Cart updated successfully',
                'cart_count' => $cartCount,
                'sub_total' => $subTotal,
                'total' => $subTotal, // Apply any discounts if needed
                'item_total' => $this->getItemTotal($cartItems, $variantId)
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to update cart'
            ]);
        }
    }
    
    /**
     * Get total price for a specific cart item
     */
    private function getItemTotal($cartItems, $variantId) {
        foreach ($cartItems as $item) {
            if ($item['id_variant'] == $variantId) {
                return $item['total_price'];
            }
        }
        return 0;
    }
    
    /**
     * Remove an item from the cart
     */
    public function removeFromCart() {
        // Check if user is logged in
        if (!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'You must be logged in to remove items from your cart'
            ]);
            return;
        }
        
        $userId = $_SESSION['id_user'];
        $variantId = isset($_POST['variant_id']) ? intval($_POST['variant_id']) : 0;
        
        if ($variantId <= 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid product variant'
            ]);
            return;
        }
        
        $result = $this->cartModel->removeFromCart($userId, $variantId);
        
        if ($result) {
            $cartItems = $this->cartModel->getCart($userId);
            $cartCount = $this->cartModel->getCartCount($userId);
            
            // Calculate new totals
            $subTotal = 0;
            foreach ($cartItems as $item) {
                $subTotal += $item['total_price'];
            }
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Item removed from cart',
                'cart_count' => $cartCount,
                'sub_total' => $subTotal,
                'total' => $subTotal // Apply any discounts if needed
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to remove item from cart'
            ]);
        }
    }
    
    /**
     * Clear the entire cart
     */
    public function clearCart() {
        // Check if user is logged in
        if (!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'You must be logged in to clear your cart'
            ]);
            return;
        }
        
        $userId = $_SESSION['id_user'];
        $result = $this->cartModel->clearCart($userId);
        
        if ($result) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Cart cleared successfully',
                'cart_count' => 0
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to clear cart'
            ]);
        }
    }
    
    /**
     * Process AJAX requests for cart operations
     */
    public function handleAjaxRequest() {
        // Ensure we are handling an AJAX request
        header('Content-Type: application/json');
        
        // Check if user is logged in
        if (!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'User not logged in'
            ]);
            exit;
        }
        
        $userId = $_SESSION['id_user'];
        $action = $_POST['action'] ?? '';
        
        switch ($action) {
            case 'add':
                $response = $this->addToCart();
                echo json_encode($response);
                break;
                
            case 'update':
                $this->updateCartItemQuantity();
                break;
                
            case 'remove':
                $this->removeFromCart();
                break;
                
            case 'clear':
                $this->clearCart();
                break;
                
            case 'remove_recent_order':
                $this->handleRemoveRecentOrder($userId);
                break;
                
            default:
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Invalid action'
                ]);
        }
    }
    
    public function placeOrder() {
        if (!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])) {
            header("Location: /web_php_mvc/View/auth/login.php");
            exit;
        }

        $userId = $_SESSION['id_user'];
        $cartItems = $this->cartModel->getCart($userId);

        $address = $_POST['address'] ?? '';
        $address_new = trim($_POST['address_new'] ?? '');
        $paymentMethod = $_POST['payment_method'] ?? '';
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['total_price'];
        }

        $finalAddress = $address_new !== '' ? $address_new : $address;

        if (empty($finalAddress) || empty($paymentMethod) || empty($cartItems)) {
            $_SESSION['order_error'] = "Please fill in all required information and check your cart!";
            header("Location: /web_php_mvc/View/user/payment.php");
            exit;
        }

        $orderModel = new Order();
        $orderId = $orderModel->createOrder($userId, $finalAddress, $paymentMethod, $cartItems, $total);

        if ($orderId) {
            $this->cartModel->clearCart($userId);
            $_SESSION['order_success'] = "Order placed successfully!";
            header("Location: /web_php_mvc/View/user/profile.php");
            exit;
        } else {
            $_SESSION['order_error'] = "An error occurred while placing the order. Please try again!";
            header("Location: /web_php_mvc/View/user/payment.php");
            exit;
        }
    }

    public function createOrder($userId, $address, $paymentMethod, $cartItems, $total) {
        // ... như hướng dẫn các bước trước ...
    }

    /**
     * Handle the remove_recent_order action
     * 
     * @param int $userId User ID
     */
    private function handleRemoveRecentOrder($userId) {
        require_once __DIR__ . '/../Model/Order.php';
        
        $orderModel = new Order();
        
        // Lấy thông tin đơn hàng gần nhất trước khi hủy để hiển thị thông báo
        $recentOrder = $orderModel->getMostRecentOrder($userId);
        
        if (!$recentOrder) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Không tìm thấy đơn hàng gần đây nào'
            ]);
            return;
        }
        
        // Kiểm tra nếu đơn hàng đã bị hủy trước đó
        if ($recentOrder['Status'] === 'Cancelled') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Đơn hàng này đã bị hủy trước đó'
            ]);
            return;
        }
        
        $result = $orderModel->cancelMostRecentOrder($userId);
        
        if ($result) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Đơn hàng #' . $recentOrder['id_invoice'] . ' đã được hủy thành công',
                'order_id' => $recentOrder['id_invoice']
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Không thể hủy đơn hàng, vui lòng thử lại sau'
            ]);
        }
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'placeOrder') {
    $controller = new CartController();
    $controller->placeOrder();
}

$userId = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : null;
if (!$userId) {
    header("Location: /web_php_mvc/View/auth/login.php");
    exit;
}
$orderModel = new Order();
$userOrders = $orderModel->getOrdersByUser($userId);

