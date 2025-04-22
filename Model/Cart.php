<?php
require_once __DIR__ . '/../config/config.php';

class Cart {
    private $db;
    
    public function __construct() {
        global $conn;
        $this->db = $conn;
    }
    
    /**
     * Add a product to the cart
     * 
     * @param int $userId User ID
     * @param int $variantId Product variant ID
     * @param int $quantity Quantity to add
     * @return bool True if successful, false otherwise
     */
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
    
    /**
     * Get all items in a user's cart
     * 
     * @param int $userId User ID
     * @return array Cart items with product details
     */
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
    
    /**
     * Update cart item quantity
     * 
     * @param int $userId User ID
     * @param int $variantId Product variant ID
     * @param int $quantity New quantity
     * @return bool True if successful, false otherwise
     */
    public function updateCartItemQuantity($userId, $variantId, $quantity) {
        try {
            if ($quantity <= 0) {
                // If quantity is 0 or negative, remove the item
                return $this->removeFromCart($userId, $variantId);
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
    
    /**
     * Remove an item from the cart
     * 
     * @param int $userId User ID
     * @param int $variantId Product variant ID
     * @return bool True if successful, false otherwise
     */
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
    
    /**
     * Clear all items from a user's cart
     * 
     * @param int $userId User ID
     * @return bool True if successful, false otherwise
     */
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
    
    /**
     * Count items in cart
     * 
     * @param int $userId User ID
     * @return int Number of items in cart
     */
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
}
