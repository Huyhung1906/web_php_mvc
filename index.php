<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define the application root path
define('ROOT_PATH', __DIR__);

// Include configuration
require_once 'config/config.php';

// Get the requested URL
$url = isset($_GET['url']) ? $_GET['url'] : '';

// Basic routing
if (empty($url)) {
    // Home page
    require_once 'Controller/usercontroller.php';
    $controller = new UserController($conn);
    $controller->index();
} elseif (preg_match('/^product-detail\/(\d+)$/', $url, $matches)) {
    // Product detail page
    require_once 'Controller/usercontroller.php';
    $controller = new UserController($conn);
    $controller->productDetail($matches[1]);
} elseif ($url == 'sneaker-shoes') {
    // Sneaker shoes category
    require_once 'Controller/usercontroller.php';
    $controller = new UserController($conn);
    $controller->sneakerShoes();
} elseif ($url == 'leather-shoes') {
    // Leather shoes category
    require_once 'Controller/usercontroller.php';
    $controller = new UserController($conn);
    $controller->leatherShoes();
} elseif ($url == 'children-shoes') {
    // Children shoes category
    require_once 'Controller/usercontroller.php';
    $controller = new UserController($conn);
    $controller->childrenShoes();
} else {
    // 404 page not found
    header("HTTP/1.0 404 Not Found");
    echo "<h1>404 - Page Not Found</h1>";
    echo "<p>The page you requested could not be found.</p>";
}
?>