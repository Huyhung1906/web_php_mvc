<?php
require_once __DIR__ . '/../config/config.php';

class Cart {
    private $db;
    
    public function __construct() {
        global $conn;
        $this->db = $conn;
    }
    
    public function addToCart($userId, $variantId, $quantity = 1) {
        try {
            error_log("Adding to cart - User ID: $userId, Variant ID: $variantId, Quantity: $quantity");
            
            // Check if this item is already in the cart
            $checkQuery = "SELECT quantity FROM cart WHERE id_user = :user_id AND id_variant = :variant_id";
            $checkStmt = $this->db->prepare($checkQuery);
            $checkStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $checkStmt->bindParam(':variant_id', $variantId, PDO::PARAM_INT);
            $checkStmt->execute();
            
            if ($checkStmt->rowCount() > 0) {
                // Update existing cart item
                $currentQuantity = $checkStmt->fetch(PDO::FETCH_ASSOC)['quantity'];
                $newQuantity = $currentQuantity + $quantity;
                
                // Implement maximum quantity limit of 20
                if ($newQuantity > 20) {
                    $newQuantity = 20;
                    error_log("Quantity limited to maximum of 20 items");
                }
                
                error_log("Updating existing cart item. Current quantity: $currentQuantity, New quantity: $newQuantity");
                
                $updateQuery = "UPDATE cart SET quantity = :quantity WHERE id_user = :user_id AND id_variant = :variant_id";
                $updateStmt = $this->db->prepare($updateQuery);
                $updateStmt->bindParam(':quantity', $newQuantity, PDO::PARAM_INT);
                $updateStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $updateStmt->bindParam(':variant_id', $variantId, PDO::PARAM_INT);
                $result = $updateStmt->execute();
                error_log("Update result: " . ($result ? "Success" : "Failed"));
                return $result;
            } else {
                // Insert new cart item
                error_log("Adding new cart item");
                
                // Implement maximum quantity limit of 20
                if ($quantity > 20) {
                    $quantity = 20;
                    error_log("Quantity limited to maximum of 20 items");
                }
                
                $insertQuery = "INSERT INTO cart (id_user, id_variant, quantity, created_date) 
                                VALUES (:user_id, :variant_id, :quantity, NOW())";
                $insertStmt = $this->db->prepare($insertQuery);
                $insertStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $insertStmt->bindParam(':variant_id', $variantId, PDO::PARAM_INT);
                $insertStmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
                $result = $insertStmt->execute();
                error_log("Insert result: " . ($result ? "Success" : "Failed"));
                return $result;
            }
        } catch (PDOException $e) {
            error_log("Database Error in addToCart: " . $e->getMessage());
            return false;
        }
    }
    
    public function getCart($userId) {
        try {
            error_log("Getting cart for User ID: $userId");
            
            // First check if there are any items in the cart at all
            $checkQuery = "SELECT COUNT(*) as count FROM cart WHERE id_user = :user_id";
            $checkStmt = $this->db->prepare($checkQuery);
            $checkStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $checkStmt->execute();
            $count = $checkStmt->fetch(PDO::FETCH_ASSOC);
            error_log("Raw cart item count for user: " . $count['count']);
            
            if ($count['count'] === 0) {
                error_log("No cart items found in database for this user");
                return array();
            }
            
            // Attempt to get full cart data with JOINs
            try {
                $query = "SELECT c.id_user, c.id_variant, c.quantity, c.created_date,
                          p.id_product, p.name_product, p.price, p.imageUrl,
                          s.size_value, col.color_name, 
                          (p.price * c.quantity) as total_price
                          FROM cart c
                          JOIN product_variant pv ON c.id_variant = pv.id_variant
                          JOIN product p ON pv.id_product = p.id_product
                          JOIN color col ON pv.id_color = col.id_color
                          JOIN size s ON pv.id_size = s.id_size
                          WHERE c.id_user = :user_id";
                          
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $stmt->execute();
                
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                error_log("Found " . count($results) . " items in cart with JOIN query");
                
                return $results;
            } catch (PDOException $joinException) {
                // If the JOIN query fails, try a simpler query as fallback
                error_log("JOIN query failed: " . $joinException->getMessage());
                error_log("Trying fallback query");
                
                $fallbackQuery = "SELECT c.id_user, c.id_variant, c.quantity, c.created_date,
                                 p.id_product, p.name_product, p.price, p.imageUrl,
                                 (p.price * c.quantity) as total_price
                                 FROM cart c
                                 JOIN product p ON c.id_variant = p.id_product
                                 WHERE c.id_user = :user_id";
                                 
                $fallbackStmt = $this->db->prepare($fallbackQuery);
                $fallbackStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $fallbackStmt->execute();
                
                $fallbackResults = $fallbackStmt->fetchAll(PDO::FETCH_ASSOC);
                error_log("Found " . count($fallbackResults) . " items in cart with fallback query");
                
                return $fallbackResults;
            }
        } catch (PDOException $e) {
            error_log("Database Error in getCart: " . $e->getMessage());
            
            // Last resort: just return basic cart data if everything else fails
            try {
                $basicQuery = "SELECT id_user, id_variant, quantity, created_date FROM cart WHERE id_user = :user_id";
                $basicStmt = $this->db->prepare($basicQuery);
                $basicStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $basicStmt->execute();
                
                $basicResults = $basicStmt->fetchAll(PDO::FETCH_ASSOC);
                error_log("Last resort: returning basic cart data. Found " . count($basicResults) . " items");
                
                return $basicResults;
            } catch (PDOException $basicException) {
                error_log("Even basic cart query failed: " . $basicException->getMessage());
                return array();
            }
        }
    }
    
    public function updateCartItemQuantity($userId, $variantId, $quantity) {
        try {
            if ($quantity <= 0) {
                // If quantity is 0 or negative, remove the item
                return $this->removeFromCart($userId, $variantId);
            }
            
            // Implement maximum quantity limit of 20
            if ($quantity > 20) {
                $quantity = 20;
                error_log("Quantity limited to maximum of 20 items in updateCartItemQuantity");
            }
            
            $query = "UPDATE cart SET quantity = :quantity 
                     WHERE id_user = :user_id AND id_variant = :variant_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':variant_id', $variantId, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database Error in updateCartItemQuantity: " . $e->getMessage());
            return false;
        }
    }
    
    public function removeFromCart($userId, $variantId) {
        try {
            $query = "DELETE FROM cart WHERE id_user = :user_id AND id_variant = :variant_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':variant_id', $variantId, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database Error in removeFromCart: " . $e->getMessage());
            return false;
        }
    }
    
    public function clearCart($userId) {
        try {
            $query = "DELETE FROM cart WHERE id_user = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database Error in clearCart: " . $e->getMessage());
            return false;
        }
    }
    
    public function getCartCount($userId) {
        try {
            $query = "SELECT SUM(quantity) as count FROM cart WHERE id_user = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] ? (int)$result['count'] : 0;
        } catch (PDOException $e) {
            error_log("Database Error in getCartCount: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get the current quantity of a specific item in the cart
     * 
     * @param int $userId User ID
     * @param int $variantId Variant ID
     * @return int Current quantity (0 if not in cart)
     */
    public function getItemQuantity($userId, $variantId) {
        try {
            $query = "SELECT quantity FROM cart WHERE id_user = :user_id AND id_variant = :variant_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':variant_id', $variantId, PDO::PARAM_INT);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return (int)$result['quantity'];
            }
            
            return 0;
        } catch (PDOException $e) {
            error_log("Database Error in getItemQuantity: " . $e->getMessage());
            return 0;
        }
    }
    
    public function getCartPromotions($userId) {
        try {
            global $conn;
            
            // Get current cart items
            $cartItems = $this->getCart($userId);
            if (empty($cartItems)) {
                return [];
            }
            
            // Extract product IDs from cart
            $productIds = [];
            foreach ($cartItems as $item) {
                if (isset($item['id_product'])) {
                    $productIds[] = $item['id_product'];
                }
            }
            
            if (empty($productIds)) {
                return [];
            }
            
            // Get current date for promotion validation
            $currentDate = date('Y-m-d');
            
            // Get promotions for these products
            $placeholders = str_repeat('?,', count($productIds) - 1) . '?';
            $query = "
                SELECT p.id_promotion, p.name_promotion, p.start_date, p.end_date, 
                       p.discount_type, p.discount_value, p.status,
                       pp.id_product, pp.promotion_price
                FROM promotions p
                JOIN promotion_product pp ON p.id_promotion = pp.id_promotion
                WHERE pp.id_product IN ($placeholders)
                AND p.status = 'active'
                AND p.start_date <= ?
                AND p.end_date >= ?
            ";
            
            $params = array_merge($productIds, [$currentDate, $currentDate]);
            
            $stmt = $conn->prepare($query);
            $stmt->execute($params);
            
            $promotions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $result = [];
            
            // Organize promotions by product
            foreach ($promotions as $promo) {
                $productId = $promo['id_product'];
                if (!isset($result[$productId])) {
                    $result[$productId] = [];
                }
                $result[$productId][] = $promo;
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Database Error in getCartPromotions: " . $e->getMessage());
            return [];
        }
    }
    
    public function applyPromotions($userId) {
        try {
            $cartItems = $this->getCart($userId);
            if (empty($cartItems)) {
                return [];
            }
            
            $promotionsByProduct = $this->getCartPromotions($userId);
            if (empty($promotionsByProduct)) {
                return $cartItems;
            }
            
            // Apply promotions to each item
            foreach ($cartItems as &$item) {
                $productId = $item['id_product'];
                
                if (isset($promotionsByProduct[$productId]) && !empty($promotionsByProduct[$productId])) {
                    // Get the best promotion for this product (lowest promotion price)
                    $bestPromotion = null;
                    $lowestPrice = $item['price'];
                    
                    foreach ($promotionsByProduct[$productId] as $promotion) {
                        if ($promotion['promotion_price'] < $lowestPrice) {
                            $bestPromotion = $promotion;
                            $lowestPrice = $promotion['promotion_price'];
                        }
                    }
                    
                    if ($bestPromotion) {
                        $item['original_price'] = $item['price'];
                        $item['price'] = $bestPromotion['promotion_price'];
                        $item['total_price'] = $item['price'] * $item['quantity'];
                        $item['promotion'] = $bestPromotion;
                        $item['discount'] = $item['original_price'] - $item['price'];
                    }
                }
            }
            
            return $cartItems;
        } catch (Exception $e) {
            error_log("Error in applyPromotions: " . $e->getMessage());
            return $cartItems;
        }
    }
}
