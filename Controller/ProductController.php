<?php
require_once 'Model/Product.php';

class ProductController {
    private $db;
    private $productModel;
    
    public function __construct($db) {
        $this->db = $db;
        $this->productModel = new Product($db);
    }
    
    public function index() {
        try {
            // Get all products using the Product model
            $products = $this->productModel->getAllProducts();
            
            // Make sure $products is at least an empty array if no results
            if (!is_array($products)) {
                $products = array();
            }
            
            // Make products available to the view
            extract(array('products' => $products));
            
            // Include the view file
            require_once 'View/user/index.php';
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    public function productDetail($id) {
        try {
            // Validate that ID is numeric
            if (!is_numeric($id)) {
                header("Location: /web_php_mvc-master/");
                exit;
            }
            
            // Get product details by ID
            $product = $this->productModel->getProductById($id);
            
            // If product not found, redirect to home page
            if (!$product) {
                header("Location: /web_php_mvc-master/");
                exit;
            }
            
            // Make product data available to the view
            extract(array('product' => $product));
            
            // Include the product detail view
            require_once 'View/user/product_detail.php';
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    public function sneakerShoes() {
        try {
            // Get sneaker shoes from the model
            $products = $this->productModel->getSneakerShoes();
            
            // Make sure $products is at least an empty array if no results
            if (!is_array($products)) {
                $products = array();
            }
            
            // Make products available to the view
            extract(array('products' => $products));
            
            // Include the view file
            require_once 'View/user/sneaker-shoes.php';
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    public function leatherShoes() {
        try {
            // Get leather shoes from the model
            $products = $this->productModel->getLeatherShoes();
            
            // Make sure $products is at least an empty array if no results
            if (!is_array($products)) {
                $products = array();
            }
            
            // Make products available to the view
            extract(array('products' => $products));
            
            // Include the view file
            require_once 'View/user/leather-shoes.php';
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    public function childrenShoes() {
        try {
            // Get children shoes from the model
            $products = $this->productModel->getChildrenShoes();
            
            // Make sure $products is at least an empty array if no results
            if (!is_array($products)) {
                $products = array();
            }
            
            // Make products available to the view
            extract(array('products' => $products));
            
            // Include the view file
            require_once 'View/user/children-shoes.php';
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
} 