<?php
class Product {
    private $db;
    
    public function __construct() {
        global $conn;
        $this->db = $conn;
    }
    
    public function getAllProducts() {
        try {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            
            $query = "SELECT * FROM product";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("Found " . count($products) . " products");
            
            if (empty($products)) {
                $checkQuery = "SELECT COUNT(*) as count FROM product";
                $checkStmt = $this->db->prepare($checkQuery);
                $checkStmt->execute();
                $count = $checkStmt->fetch(PDO::FETCH_ASSOC);
                error_log("Product table count: " . $count['count']);
                
                error_log("Database connection info - Host: " . $this->db->getAttribute(PDO::ATTR_CONNECTION_STATUS));
            }
            
            return $products;
        } catch(PDOException $e) {
            error_log("Database Error in getAllProducts: " . $e->getMessage());
            return array();
        }
    }

    public function getSneakerShoes($filters = array()) {
        try {
            $query = "SELECT * FROM product p 
                    INNER JOIN line l ON p.id_line = l.id_line 
                    INNER JOIN category c ON c.id_category = l.id_category 
                    WHERE c.id_category = 1";
            $params = array();
            
            if (!empty($filters['size'])) {
                $query .= " AND size = :size";
                $params[':size'] = $filters['size'];
            }
            
            if (!empty($filters['price_min'])) {
                $query .= " AND price >= :price_min";
                $params[':price_min'] = $filters['price_min'];
            }
            if (!empty($filters['price_max'])) {
                $query .= " AND price <= :price_max";
                $params[':price_max'] = $filters['price_max'];
            }
            
            if (!empty($filters['sort'])) {
                switch($filters['sort']) {
                    case 'price_asc':
                        $query .= " ORDER BY price ASC";
                        break;
                    case 'price_desc':
                        $query .= " ORDER BY price DESC";
                        break;
                    case 'newest':
                        $query .= " ORDER BY created_at DESC";
                        break;
                    case 'popular':
                        $query .= " ORDER BY views DESC";
                        break;
                    default:
                        $query .= " ORDER BY id_product DESC";
                }
            } else {
                $query .= " ORDER BY id_product DESC";
            }
            
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            $sneakers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $sneakers;
        } catch(PDOException $e) {
            error_log("Database Error in getSneakers: " . $e->getMessage());
            return array();
        }
    }

    public function getLeatherShoes($filters = array()) {
        try {
            $query = "SELECT * FROM product p 
                    INNER JOIN line l ON p.id_line = l.id_line 
                    INNER JOIN category c ON c.id_category = l.id_category 
                    WHERE c.id_category = 2";
            $params = array();
            
            if (!empty($filters['size'])) {
                $query .= " AND size = :size";
                $params[':size'] = $filters['size'];
            }
            
            if (!empty($filters['price_min'])) {
                $query .= " AND price >= :price_min";
                $params[':price_min'] = $filters['price_min'];
            }
            if (!empty($filters['price_max'])) {
                $query .= " AND price <= :price_max";
                $params[':price_max'] = $filters['price_max'];
            }
            
            if (!empty($filters['sort'])) {
                switch($filters['sort']) {
                    case 'price_asc':
                        $query .= " ORDER BY price ASC";
                        break;
                    case 'price_desc':
                        $query .= " ORDER BY price DESC";
                        break;
                    case 'newest':
                        $query .= " ORDER BY created_at DESC";
                        break;
                    case 'popular':
                        $query .= " ORDER BY views DESC";
                        break;
                    default:
                        $query .= " ORDER BY id_product DESC";
                }
            } else {
                $query .= " ORDER BY id_product DESC";
            }
            
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            $leatherShoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $leatherShoes;
        } catch(PDOException $e) {
            error_log("Database Error in getLeatherShoes: " . $e->getMessage());
            return array();
        }
    }

    public function getChildrenShoes($filters = array()) {
        try {
            $query = "SELECT * FROM product p 
                    INNER JOIN line l ON p.id_line = l.id_line 
                    INNER JOIN category c ON c.id_category = l.id_category 
                    WHERE c.id_category = 3";
            $params = array();
            
            if (!empty($filters['size'])) {
                $query .= " AND size = :size";
                $params[':size'] = $filters['size'];
            }
            
            if (!empty($filters['price_min'])) {
                $query .= " AND price >= :price_min";
                $params[':price_min'] = $filters['price_min'];
            }
            if (!empty($filters['price_max'])) {
                $query .= " AND price <= :price_max";
                $params[':price_max'] = $filters['price_max'];
            }
        
            if (!empty($filters['sort'])) {
                switch($filters['sort']) {
                    case 'price_asc':
                        $query .= " ORDER BY price ASC";
                        break;
                    case 'price_desc':
                        $query .= " ORDER BY price DESC";
                        break;
                    case 'newest':
                        $query .= " ORDER BY created_at DESC";
                        break;
                    case 'popular':
                        $query .= " ORDER BY views DESC";
                        break;
                    default:
                        $query .= " ORDER BY id_product DESC";
                }
            } else {
                $query .= " ORDER BY id_product DESC";
            }
            
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            $childrenShoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $childrenShoes;
        } catch(PDOException $e) {
            error_log("Database Error in getChildrenShoes: " . $e->getMessage());
            return array();
        }
    }
    
    public function getProductById($product_id) {
        try {
            $query = "SELECT p.*, b.name_brand, l.name_category as line_name, c.name_category as category_name 
                      FROM product p
                      JOIN brand b ON p.id_brand = b.id_brand
                      JOIN line l ON p.id_line = l.id_line
                      JOIN category c ON l.id_category = c.id_category
                      WHERE p.id_product = :id_product";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_product', $product_id, PDO::PARAM_INT);
            $stmt->execute();
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$product) {
                return null;
            }
            
            $variantQuery = "SELECT pv.*, s.size_value, s.type as size_type, c.color_name, c.color_code 
                            FROM product_variant pv
                            JOIN size s ON pv.id_size = s.id_size
                            JOIN color c ON pv.id_color = c.id_color
                            WHERE pv.id_product = :id_product";
            
            error_log("Running variant query for Product ID: $product_id");
            error_log("SQL: $variantQuery");
            
            try {
                $variantStmt = $this->db->prepare($variantQuery);
                $variantStmt->bindParam(':id_product', $product_id, PDO::PARAM_INT);
                $variantStmt->execute();
                $product['variants'] = $variantStmt->fetchAll(PDO::FETCH_ASSOC);
                
                error_log("Product ID: $product_id - " . count($product['variants']) . " variants found");
                
                if (empty($product['variants'])) {
                    
                    $product['variants'] = [
                        [
                            'id_size' => 1,
                            'size_value' => '40',
                            'size_type' => 'EU',
                            'quantity' => 10,
                            'id_color' => 1,
                            'color_name' => 'Black',
                            'color_code' => '#000000'
                        ],
                        [
                            'id_size' => 2,
                            'size_value' => '41',
                            'size_type' => 'EU',
                            'quantity' => 5,
                            'id_color' => 1,
                            'color_name' => 'Black',
                            'color_code' => '#000000'
                        ],
                        [
                            'id_size' => 3,
                            'size_value' => '42',
                            'size_type' => 'EU',
                            'quantity' => 8,
                            'id_color' => 1,
                            'color_name' => 'Black',
                            'color_code' => '#000000'
                        ]
                    ];
                }
            } catch (PDOException $e) {
                error_log("Error fetching variants: " . $e->getMessage());
                $product['variants'] = [];
            }
            
            $sizes = [];
            foreach ($product['variants'] as $variant) {
                $sizes[$variant['id_size']] = [
                    'id_size' => $variant['id_size'],
                    'size_value' => $variant['size_value'],
                    'size_type' => $variant['size_type']
                ];
            }
            
            $product['available_sizes'] = array_values($sizes);
            
            $imageQuery = "SELECT * FROM image WHERE id_product = :id_product ORDER BY isPrimary DESC";
            $imageStmt = $this->db->prepare($imageQuery);
            $imageStmt->bindParam(':id_product', $product_id, PDO::PARAM_INT);
            $imageStmt->execute();
            $product['images'] = $imageStmt->fetchAll(PDO::FETCH_ASSOC);
            
            $promotionQuery = "SELECT pp.promotion_price, p.* 
                              FROM promotions_product pp
                              JOIN promotions p ON pp.id_promotion = p.id_promotions
                              WHERE pp.id_product = :id_product
                              AND p.status = 1
                              AND CURRENT_DATE BETWEEN p.start_date AND p.end_date";
            
            $promotionStmt = $this->db->prepare($promotionQuery);
            $promotionStmt->bindParam(':id_product', $product_id, PDO::PARAM_INT);
            $promotionStmt->execute();
            $product['promotions'] = $promotionStmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $product;
        } catch(PDOException $e) {
            error_log("Database Error in getProductById: " . $e->getMessage());
            return null;
        }
    }

}
?> 