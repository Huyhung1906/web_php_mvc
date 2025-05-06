<?php
require_once __DIR__ . '/../../Model/productVariant.php';

class ProductVariant {
    private $model;

    public function __construct() {
        $this->model = new ProductVariantModel();
    }

    // Hiển thị trang quản lý biến thể
    public function index() {
        $products = $this->model->getAllProducts();
        $sizes = $this->model->getAllSizes();
        $colors = $this->model->getAllColors();
        require_once ROOT_PATH . '/View/admin/product_variants.php';
    }

    // Xử lý AJAX request lấy danh sách biến thể
    public function getVariants() {
        // Bắt lỗi HTML/PHP warnings ra buffer
        ob_start();
        ini_set('display_errors', 0);
        error_reporting(0);
        try {
            $filters = json_decode(file_get_contents('php://input'), true);
            $sql = "SELECT pv.id_variant, p.name_product, sz.size_value, c.color_name, pv.quantity, pv.expired_date "
                 . "FROM product_variant pv "
                 . "INNER JOIN product p ON pv.id_product = p.id_product "
                 . "INNER JOIN size sz ON pv.id_size = sz.id_size "
                 . "INNER JOIN color c ON pv.id_color = c.id_color "
                 . "WHERE 1=1";
            $params = [];
            if (!empty($filters['product'])) {
                $sql .= " AND pv.id_product = :product";
                $params[':product'] = $filters['product'];
            }
            if (!empty($filters['size'])) {
                $sql .= " AND pv.id_size = :size";
                $params[':size'] = $filters['size'];
            }
            if (!empty($filters['color'])) {
                $sql .= " AND pv.id_color = :color";
                $params[':color'] = $filters['color'];
            }
            $variants = $this->model->getFilteredVariants($sql, $params);
            // Xoá sạch buffer (loại bỏ HTML warnings nếu có)
            ob_end_clean();
            header('Content-Type: application/json');
            echo json_encode($variants);
            exit;
        } catch (Exception $e) {
            ob_end_clean();
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }

    // Hiển thị form thêm biến thể
    public function showAddForm() {
        $products = $this->model->getAllProducts();
        $sizes = $this->model->getAllSizes();
        $colors = $this->model->getAllColors();
        require_once ROOT_PATH . '/View/admin/add_product_variant.php';
    }

    // Xử lý thêm biến thể
    public function addVariant() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validate dữ liệu
            if (empty($data['product']) || empty($data['size']) || empty($data['color']) || 
                !isset($data['quantity']) || empty($data['expired_date'])) {
                throw new Exception('Vui lòng điền đầy đủ thông tin');
            }

            // Kiểm tra biến thể đã tồn tại chưa
            $exists = $this->model->checkVariantExists($data['product'], $data['size'], $data['color']);
            if ($exists) {
                throw new Exception('Biến thể này đã tồn tại');
            }

            // Thêm biến thể mới
            $result = $this->model->addVariant([
                'product' => $data['product'],
                'size' => $data['size'],
                'color' => $data['color'],
                'quantity' => $data['quantity'],
                'expired' => $data['expired_date']
            ]);

            if ($result) {
                echo json_encode(['success' => true]);
            } else {
                throw new Exception('Thêm biến thể thất bại');
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }

    // Hiển thị form sửa biến thể
    public function showEditForm($id) {
        $variant = $this->model->getVariantById($id);
        $products = $this->model->getAllProducts();
        $sizes = $this->model->getAllSizes();
        $colors = $this->model->getAllColors();
        require_once ROOT_PATH . '/View/admin/edit_product_variant.php';
    }

    // Xử lý cập nhật biến thể
    public function updateVariant($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'product' => $_POST['product'],
                'size'    => $_POST['size'],
                'color'   => $_POST['color'],
                'quantity'=> $_POST['quantity'],
                'expired' => $_POST['expired_date']
            ];
            $this->model->updateVariant($id, $data);
            echo "<script>alert('Cập nhật biến thể thành công'); window.location.href='/web_php_mvc/admin/product_variants';</script>";
            exit;
        }
    }

    // Xử lý xóa biến thể
    public function deleteVariant($id) {
        $result = $this->model->deleteVariant($id);
        echo json_encode(['success' => $result]);
    }
}
?>
