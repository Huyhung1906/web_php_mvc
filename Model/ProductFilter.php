<?php
class ProductFilter {
    private $db;
    
    public function __construct() {
        global $conn;
        $this->db = $conn;
    }
    
    /**
     * Parse filter parameters from request
     * 
     * @return array Array of filter parameters
     */
    public function parseFilterParams() {
        $filters = [];
        
        // Size filter
        if (isset($_GET['size']) && $_GET['size'] !== 'all') {
            $filters['size'] = $_GET['size'];
        }
        
        // Price range filter
        if (isset($_GET['price']) && $_GET['price'] !== 'all') {
            switch ($_GET['price']) {
                case 'under_1m':
                    $filters['price_max'] = 1000000;
                    break;
                case '1m_2m':
                    $filters['price_min'] = 1000000;
                    $filters['price_max'] = 2000000;
                    break;
                case '2m_3m':
                    $filters['price_min'] = 2000000;
                    $filters['price_max'] = 3000000;
                    break;
                case 'over_3m':
                    $filters['price_min'] = 3000000;
                    break;
            }
        }
        
        // Sort filter
        if (isset($_GET['sort'])) {
            $filters['sort'] = $_GET['sort'];
        }
        
        return $filters;
    }
    
    /**
     * Get available sizes for products
     * 
     * @return array Array of available sizes
     */
    public function getAvailableSizes() {
        try {
            $query = "SELECT DISTINCT size_value FROM size ORDER BY size_value ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $sizes = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            return $sizes ?: ['36', '37', '38', '39', '40', '41', '42', '43'];
        } catch(PDOException $e) {
            error_log("Database Error in getAvailableSizes: " . $e->getMessage());
            return ['36', '37', '38', '39', '40', '41', '42', '43'];
        }
    }
    
    /**
     * Apply filters to all product queries
     * 
     * @param string $query The base SQL query
     * @param array $params Query parameters
     * @param array $filters Filter parameters
     * @return array Modified query and parameters
     */
    public function applyFilters($query, $params, $filters) {
        if (!empty($filters['size'])) {
            // Join with the size table to filter by size
            if (strpos($query, 'JOIN size') === false) {
                $query = str_replace('WHERE', 'JOIN product_variant pv ON p.id_product = pv.id_product JOIN size s ON pv.id_size = s.id_size WHERE', $query);
            }
            $query .= " AND s.size_value = :size";
            $params[':size'] = $filters['size'];
        }
        
        if (!empty($filters['price_min'])) {
            $query .= " AND p.price >= :price_min";
            $params[':price_min'] = $filters['price_min'];
        }
        
        if (!empty($filters['price_max'])) {
            $query .= " AND p.price <= :price_max";
            $params[':price_max'] = $filters['price_max'];
        }
        
        if (!empty($filters['sort'])) {
            switch($filters['sort']) {
                case 'price_asc':
                    $query .= " ORDER BY p.price ASC";
                    break;
                case 'price_desc':
                    $query .= " ORDER BY p.price DESC";
                    break;
                case 'newest':
                    $query .= " ORDER BY p.created_at DESC";
                    break;
                case 'popular':
                    $query .= " ORDER BY p.views DESC";
                    break;
                default:
                    $query .= " ORDER BY p.id_product DESC";
            }
        } else {
            $query .= " ORDER BY p.id_product DESC";
        }
        
        // Ensure distinct products if we joined with variants
        if (strpos($query, 'JOIN product_variant') !== false) {
            $query = "SELECT DISTINCT p.* FROM (" . $query . ") as p";
        }
        
        return [
            'query' => $query,
            'params' => $params
        ];
    }
} 