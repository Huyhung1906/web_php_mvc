<?php
class Product {
    private $db;
    private $productsPerPage = 9; // Default products per page
    private $sneakersPerPage = 6; // Specific count for sneakers
    private $leatherPerPage = 9;  // Specific count for leather shoes
    private $childrenPerPage = 9; // Specific count for children shoes
    
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
            // Mặc định là trang 1 nếu không xác định
            $page = isset($filters['page']) ? (int)$filters['page'] : 1;
            if ($page < 1) $page = 1;
            
            // Đếm tổng số sản phẩm để tính toán phân trang
            $countQuery = "SELECT COUNT(*) as total FROM product p 
                          INNER JOIN line l ON p.id_line = l.id_line 
                          INNER JOIN category c ON c.id_category = l.id_category 
                          WHERE c.id_category = 1";
            $countParams = array();
            
            if (!empty($filters['size'])) {
                $countQuery .= " AND size = :size";
                $countParams[':size'] = $filters['size'];
            }
            
            if (!empty($filters['price_min'])) {
                $countQuery .= " AND price >= :price_min";
                $countParams[':price_min'] = $filters['price_min'];
            }
            if (!empty($filters['price_max'])) {
                $countQuery .= " AND price <= :price_max";
                $countParams[':price_max'] = $filters['price_max'];
            }
            
            $countStmt = $this->db->prepare($countQuery);
            $countStmt->execute($countParams);
            $totalCount = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Tính toán thông tin phân trang - sử dụng sneakersPerPage thay vì productsPerPage
            $totalPages = ceil($totalCount / $this->sneakersPerPage);
            $offset = ($page - 1) * $this->sneakersPerPage;
            
            // Truy vấn dữ liệu với phân trang
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
            
            // Thêm LIMIT và OFFSET cho phân trang - sử dụng sneakersPerPage
            $query .= " LIMIT :limit OFFSET :offset";
            $params[':limit'] = $this->sneakersPerPage;
            $params[':offset'] = $offset;
            
            $stmt = $this->db->prepare($query);
            
            // Bind các tham số
            foreach ($params as $key => $value) {
                if ($key == ':limit' || $key == ':offset') {
                    $stmt->bindValue($key, $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue($key, $value);
                }
            }
            
            $stmt->execute();
            $sneakers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Trả về kết quả với thông tin phân trang
            return [
                'products' => $sneakers,
                'pagination' => [
                    'total' => $totalCount,
                    'per_page' => $this->sneakersPerPage,
                    'current_page' => $page,
                    'last_page' => $totalPages,
                    'from' => $offset + 1,
                    'to' => min($offset + $this->sneakersPerPage, $totalCount)
                ]
            ];
        } catch(PDOException $e) {
            error_log("Database Error in getSneakers: " . $e->getMessage());
            return [
                'products' => array(),
                'pagination' => [
                    'total' => 0,
                    'per_page' => $this->sneakersPerPage,
                    'current_page' => 1,
                    'last_page' => 1,
                    'from' => 0,
                    'to' => 0
                ]
            ];
        }
    }

    public function getLeatherShoes($filters = array()) {
        try {
            // Mặc định là trang 1 nếu không xác định
            $page = isset($filters['page']) ? (int)$filters['page'] : 1;
            if ($page < 1) $page = 1;
            
            // Đếm tổng số sản phẩm để tính toán phân trang
            $countQuery = "SELECT COUNT(*) as total FROM product p 
                          INNER JOIN line l ON p.id_line = l.id_line 
                          INNER JOIN category c ON c.id_category = l.id_category 
                          WHERE c.id_category = 2";
            $countParams = array();
            
            if (!empty($filters['size'])) {
                $countQuery .= " AND size = :size";
                $countParams[':size'] = $filters['size'];
            }
            
            if (!empty($filters['price_min'])) {
                $countQuery .= " AND price >= :price_min";
                $countParams[':price_min'] = $filters['price_min'];
            }
            if (!empty($filters['price_max'])) {
                $countQuery .= " AND price <= :price_max";
                $countParams[':price_max'] = $filters['price_max'];
            }
            
            $countStmt = $this->db->prepare($countQuery);
            $countStmt->execute($countParams);
            $totalCount = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Tính toán thông tin phân trang - sử dụng leatherPerPage
            $totalPages = ceil($totalCount / $this->leatherPerPage);
            $offset = ($page - 1) * $this->leatherPerPage;
            
            // Truy vấn dữ liệu với phân trang
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
            
            // Thêm LIMIT và OFFSET cho phân trang
            $query .= " LIMIT :limit OFFSET :offset";
            $params[':limit'] = $this->leatherPerPage;
            $params[':offset'] = $offset;
            
            $stmt = $this->db->prepare($query);
            
            // Bind các tham số
            foreach ($params as $key => $value) {
                if ($key == ':limit' || $key == ':offset') {
                    $stmt->bindValue($key, $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue($key, $value);
                }
            }
            
            $stmt->execute();
            $leatherShoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Trả về kết quả với thông tin phân trang
            return [
                'products' => $leatherShoes,
                'pagination' => [
                    'total' => $totalCount,
                    'per_page' => $this->leatherPerPage,
                    'current_page' => $page,
                    'last_page' => $totalPages,
                    'from' => $offset + 1,
                    'to' => min($offset + $this->leatherPerPage, $totalCount)
                ]
            ];
        } catch(PDOException $e) {
            error_log("Database Error in getLeatherShoes: " . $e->getMessage());
            return [
                'products' => array(),
                'pagination' => [
                    'total' => 0,
                    'per_page' => $this->leatherPerPage,
                    'current_page' => 1,
                    'last_page' => 1,
                    'from' => 0,
                    'to' => 0
                ]
            ];
        }
    }

    public function getChildrenShoes($filters = array()) {
        try {
            // Mặc định là trang 1 nếu không xác định
            $page = isset($filters['page']) ? (int)$filters['page'] : 1;
            if ($page < 1) $page = 1;
            
            // Đếm tổng số sản phẩm để tính toán phân trang
            $countQuery = "SELECT COUNT(*) as total FROM product p 
                          INNER JOIN line l ON p.id_line = l.id_line 
                          INNER JOIN category c ON c.id_category = l.id_category 
                          WHERE c.id_category = 3";
            $countParams = array();
            
            if (!empty($filters['size'])) {
                $countQuery .= " AND size = :size";
                $countParams[':size'] = $filters['size'];
            }
            
            if (!empty($filters['price_min'])) {
                $countQuery .= " AND price >= :price_min";
                $countParams[':price_min'] = $filters['price_min'];
            }
            if (!empty($filters['price_max'])) {
                $countQuery .= " AND price <= :price_max";
                $countParams[':price_max'] = $filters['price_max'];
            }
            
            $countStmt = $this->db->prepare($countQuery);
            $countStmt->execute($countParams);
            $totalCount = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Tính toán thông tin phân trang - sử dụng childrenPerPage
            $totalPages = ceil($totalCount / $this->childrenPerPage);
            $offset = ($page - 1) * $this->childrenPerPage;
            
            // Truy vấn dữ liệu với phân trang
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
            
            // Thêm LIMIT và OFFSET cho phân trang
            $query .= " LIMIT :limit OFFSET :offset";
            $params[':limit'] = $this->childrenPerPage;
            $params[':offset'] = $offset;
            
            $stmt = $this->db->prepare($query);
            
            // Bind các tham số
            foreach ($params as $key => $value) {
                if ($key == ':limit' || $key == ':offset') {
                    $stmt->bindValue($key, $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue($key, $value);
                }
            }
            
            $stmt->execute();
            $childrenShoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Trả về kết quả với thông tin phân trang
            return [
                'products' => $childrenShoes,
                'pagination' => [
                    'total' => $totalCount,
                    'per_page' => $this->childrenPerPage,
                    'current_page' => $page,
                    'last_page' => $totalPages,
                    'from' => $offset + 1,
                    'to' => min($offset + $this->childrenPerPage, $totalCount)
                ]
            ];
        } catch(PDOException $e) {
            error_log("Database Error in getChildrenShoes: " . $e->getMessage());
            return [
                'products' => array(),
                'pagination' => [
                    'total' => 0,
                    'per_page' => $this->childrenPerPage,
                    'current_page' => 1,
                    'last_page' => 1,
                    'from' => 0,
                    'to' => 0
                ]
            ];
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

    /**
     * Helper function to build pagination links
     * 
     * @param array $pagination Pagination data
     * @param array $filters Current filters
     * @return string HTML for pagination links
     */
    public function buildPaginationLinks($pagination, $filters) {
        $currentPage = $pagination['current_page'];
        $lastPage = $pagination['last_page'];
        
        // Get current URL path
        $currentUrl = $_SERVER['PHP_SELF'];
        
        // Remove 'page' from filters for building page links
        $queryFilters = $filters;
        unset($queryFilters['page']);
        
        // Build query string from current filters
        $queryString = http_build_query($queryFilters);
        $queryPrefix = empty($queryString) ? '?' : "?{$queryString}&";
        
        $html = '<div class="row">';
        $html .= '<div class="col-md-12 text-center">';
        $html .= '<div class="block-27">';
        $html .= '<ul>';
        
        // Previous page link
        if ($currentPage > 1) {
            $html .= '<li><a href="' . $currentUrl . $queryPrefix . 'page=' . ($currentPage - 1) . '">&lt;</a></li>';
        } else {
            $html .= '<li class="disabled"><span>&lt;</span></li>';
        }
        
        // Page numbers
        $startPage = max(1, $currentPage - 2);
        $endPage = min($lastPage, $currentPage + 2);
        
        // Always show page 1
        if ($startPage > 1) {
            $html .= '<li><a href="' . $currentUrl . $queryPrefix . 'page=1">1</a></li>';
            if ($startPage > 2) {
                $html .= '<li class="disabled"><span>...</span></li>';
            }
        }
        
        // Page numbers
        for ($i = $startPage; $i <= $endPage; $i++) {
            if ($i == $currentPage) {
                $html .= '<li class="active"><span>' . $i . '</span></li>';
            } else {
                $html .= '<li><a href="' . $currentUrl . $queryPrefix . 'page=' . $i . '">' . $i . '</a></li>';
            }
        }
        
        // Always show last page
        if ($endPage < $lastPage) {
            if ($endPage < $lastPage - 1) {
                $html .= '<li class="disabled"><span>...</span></li>';
            }
            $html .= '<li><a href="' . $currentUrl . $queryPrefix . 'page=' . $lastPage . '">' . $lastPage . '</a></li>';
        }
        
        // Next page link
        if ($currentPage < $lastPage) {
            $html .= '<li><a href="' . $currentUrl . $queryPrefix . 'page=' . ($currentPage + 1) . '">&gt;</a></li>';
        } else {
            $html .= '<li class="disabled"><span>&gt;</span></li>';
        }
        
        $html .= '</ul>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
}
?> 