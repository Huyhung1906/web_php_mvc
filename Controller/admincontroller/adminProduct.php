<?php
require_once 'Model/adminProductModel.php';

class AdminProduct {
    private $model;

    public function __construct() {
        $this->model = new AdminProductModel();
    }

    // Hiển thị trang quản lý sản phẩm
    public function index() {
        $brands = $this->model->getAllBrands();
        $categories = $this->model->getAllCategories();
        // Khởi tạo giá trị filter mặc định từ GET (nếu có)
        $initFilters = [
            'category' => $_GET['category'] ?? '',
            'brand'    => $_GET['brand'] ?? '',
            'status'   => $_GET['status'] ?? '',
            'search'   => $_GET['search'] ?? ''
        ];
        require_once ROOT_PATH . '/View/admin/products.php';
    }

    // Xử lý AJAX request lấy danh sách sản phẩm
    public function getProducts() {
        try {
            $filters = json_decode(file_get_contents('php://input'), true);
            
            $sql = "SELECT p.*, b.name_brand, l.name_category, c.name_category as category_name FROM product p LEFT JOIN brand b ON p.id_brand = b.id_brand LEFT JOIN line l ON p.id_line = l.id_line LEFT JOIN category c ON l.id_category = c.id_category WHERE 1=1";
            
            $params = [];
            
            if (!empty($filters['category'])) {
                $sql .= " AND c.id_category = :category";
                $params[':category'] = $filters['category'];
            }
            
            if (!empty($filters['brand'])) {
                $sql .= " AND p.id_brand = :brand";
                $params[':brand'] = $filters['brand'];
            }
            
            if (!empty($filters['status'])) {
                $sql .= " AND p.status = :status";
                $params[':status'] = $filters['status'];
            }
            
            if (!empty($filters['search'])) {
                $sql .= " AND p.name_product LIKE :search";
                $params[':search'] = '%' . $filters['search'] . '%';
            }
            
            $products = $this->model->getFilteredProducts($sql, $params);
            
            header('Content-Type: application/json');
            echo json_encode($products);
            exit;
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }

    // Hiển thị form thêm sản phẩm
    public function showAddForm() {
        $brands = $this->model->getAllBrands();
        $categories = $this->model->getAllCategories();
        $lines = $this->model->getAllLines();
        require_once ROOT_PATH . '/View/admin/add_product.php';
    }

    // Xử lý thêm sản phẩm
    public function addProduct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'line' => $_POST['line'],
                'brand' => $_POST['brand'],
                'description' => $_POST['description'],
                'material' => $_POST['material'],
                'price' => $_POST['price'],
                'image' => $this->handleImageUpload(),
                'release' => $_POST['release'],
                'status' => $_POST['status']
            ];

            $productId = $this->model->addProduct($data);
            
            // Xử lý upload nhiều hình ảnh
            if (isset($_FILES['images'])) {
                $this->handleMultipleImages($productId);
            }

            echo "<script>alert('Thêm sản phẩm thành công'); window.location.href='/web_php_mvc/View/admin/products.php';</script>";
            exit;
        }   
    }

    // Hiển thị form sửa sản phẩm
    public function showEditForm($id) {
        $product = $this->model->getProductById($id);
        $images = $this->model->getProductImages($id);
        $brands = $this->model->getAllBrands();
        $categories = $this->model->getAllCategories();
        $lines = $this->model->getAllLines();
        require_once ROOT_PATH . '/View/admin/edit_product.php';
    }

    // Xử lý sửa sản phẩm
    public function updateProduct($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'line' => $_POST['line'],
                'brand' => $_POST['brand'],
                'description' => $_POST['description'],
                'material' => $_POST['material'],
                'price' => $_POST['price'],
                'release' => $_POST['release'],
                'status' => $_POST['status']
            ];

            // Xử lý hình ảnh mới nếu có
            if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
                $data['image'] = $this->handleImageUpload();
            } else {
                $data['image'] = $_POST['current_image'];
            }

            $this->model->updateProduct($id, $data);

            // Xử lý upload nhiều hình ảnh mới
            if (isset($_FILES['images'])) {
                $this->handleMultipleImages($id);
            }

            echo "<script>alert('Cập nhật sản phẩm thành công'); window.location.href='/web_php_mvc/View/admin/products.php';</script>";
            exit;
        }
    }

    // Xử lý xóa sản phẩm
    public function deleteProduct($id) {
        $result = $this->model->deleteProduct($id);
        echo json_encode(['success' => $result]);
    }

    // Xử lý upload hình ảnh
    private function handleImageUpload() {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $target_dir = __DIR__ . '/../../public/images/';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
            $new_filename = uniqid() . '.' . $file_extension;
            $target_file = rtrim($target_dir, '/\\') . DIRECTORY_SEPARATOR . $new_filename;

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                return $new_filename;
            }
        }
        return null;
    }

    // Xử lý upload nhiều hình ảnh
    private function handleMultipleImages($productId) {
        if (isset($_FILES['images'])) {
            $target_dir = __DIR__ . '/../../public/images/';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['images']['error'][$key] === 0) {
                    $file_extension = strtolower(pathinfo($_FILES["images"]["name"][$key], PATHINFO_EXTENSION));
                    $new_filename = uniqid() . '.' . $file_extension;
                    $target_file = rtrim($target_dir, '/\\') . DIRECTORY_SEPARATOR . $new_filename;

                    if (move_uploaded_file($tmp_name, $target_file)) {
                        $imageData = [
                            'imageUrl' => $new_filename,
                            'isPrimary' => ($key === 0) ? 1 : 0,
                            'productId' => $productId,
                            'variantId' => null,
                            'colorId' => null
                        ];
                        $this->model->addProductImage($imageData);
                    }
                }
            }
        }
    }

    // Xử lý xóa hình ảnh
    public function deleteImage($imageId) {
        $result = $this->model->deleteProductImage($imageId);
        echo json_encode(['success' => $result]);
    }
}
?>
