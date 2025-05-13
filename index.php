<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Debug: Log request URL
error_log('Request URL: ' . $_SERVER['REQUEST_URI']);

// Define the application root path
define('ROOT_PATH', __DIR__);

// Include configuration
require_once 'config/config.php';

// Include cart controller
require_once 'Controller/CartController.php';

// Get the requested URL
$url = isset($_GET['url']) ? $_GET['url'] : '';
// Lấy URL từ request
$url = $_SERVER['REQUEST_URI'];
$url = str_replace('/web_php_mvc', '', $url);
$url = trim($url, '/');

// Debug: Log processed URL
error_log('Processed URL: ' . $url);

// Check controller parameter
$controller = isset($_GET['controller']) ? $_GET['controller'] : '';
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Handle admin routes
if (strpos($url, 'admin/') === 0) {
    $adminPath = substr($url, 6); // Remove 'admin/' from the URL
    
    // Debug: Log admin path
    error_log('Admin path: ' . $adminPath);
    
    if ($adminPath === 'products') {
        require_once 'Controller/admincontroller/adminProduct.php';
        $adminProduct = new AdminProduct();
        $adminProduct->index();
    } elseif (preg_match('/^products\/get-products$/', $adminPath)) {
        require_once 'Controller/admincontroller/adminProduct.php';
        $adminProduct = new AdminProduct();
        $adminProduct->getProducts();
    } elseif (preg_match('/^products\/add$/', $adminPath)) {
        require_once 'Controller/admincontroller/adminProduct.php';
        $adminProduct = new AdminProduct();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adminProduct->addProduct();
        } else {
            $adminProduct->showAddForm();
        }
    } elseif (preg_match('/^products\/edit\/(\d+)$/', $adminPath, $matches)) {
        require_once 'Controller/admincontroller/adminProduct.php';
        $adminProduct = new AdminProduct();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adminProduct->updateProduct($matches[1]);
        } else {
            $adminProduct->showEditForm($matches[1]);
        }
    } elseif (preg_match('/^products\/delete\/(\d+)$/', $adminPath, $matches)) {
        require_once 'Controller/admincontroller/adminProduct.php';
        $adminProduct = new AdminProduct();
        $adminProduct->deleteProduct($matches[1]);
    } elseif (preg_match('/^products\/delete-image\/(\d+)$/', $adminPath, $matches)) {
        require_once 'Controller/admincontroller/adminProduct.php';
        $adminProduct = new AdminProduct();
        $adminProduct->deleteImage($matches[1]);
    } 
    exit;
}
// Handle different controllers
else if ($controller === 'cart') {
    $cartController = new CartController();
    
    if ($action === 'viewCart') {
        $cartController->viewCart();
    } else {
        // Default action for cart controller
        $cartController->viewCart();
    }
} else if ($controller === 'checkout') {
    // Handle checkout controller when implemented
    // $checkoutController = new CheckoutController();
    // $checkoutController->index();
} else {
    // Basic routing
    if (empty($url)) {
        // Home page
        require_once 'Controller/ProductController.php';
        $controller = new ProductController($conn);
        $controller->index();
    } elseif (preg_match('/^product-detail\/(\d+)$/', $url, $matches)) {
        // Product detail page
        require_once 'Controller/ProductController.php';
        $controller = new ProductController($conn);
        $controller->productDetail($matches[1]);
    } elseif ($url == 'sneaker-shoes') {
        // Sneaker shoes category
        require_once 'Controller/ProductController.php';
        $controller = new ProductController($conn);
        $controller->sneakerShoes();
    } elseif ($url == 'leather-shoes') {
        // Leather shoes category
        require_once 'Controller/ProductController.php';
        $controller = new ProductController($conn);
        $controller->leatherShoes();
    } elseif ($url == 'children-shoes') {
        // Children shoes category
        require_once 'Controller/ProductController.php';
        $controller = new ProductController($conn);
        $controller->childrenShoes();
    } else {
        // 404 page not found
        header("HTTP/1.0 404 Not Found");
        echo "<h1>404 - Page Not Found</h1>";
        echo "<p>The page you requested could not be found.</p>";
    }
}
?>