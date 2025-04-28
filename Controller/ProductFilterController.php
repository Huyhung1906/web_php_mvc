<?php
require_once __DIR__ . '/../Model/Product.php';
require_once __DIR__ . '/../Model/ProductFilter.php';

class ProductFilterController {
    private $productModel;
    private $filterModel;
    
    public function __construct() {
        $this->productModel = new Product();
        $this->filterModel = new ProductFilter();
    }
    
    /**
     * Get filtered sneaker shoes
     * 
     * @return array Filtered sneaker shoes
     */
    public function getFilteredSneakers() {
        $filters = $this->filterModel->parseFilterParams();
        $sizes = $this->filterModel->getAvailableSizes();
        
        $sneakers = $this->productModel->getSneakerShoes($filters);
        
        return [
            'products' => $sneakers,
            'sizes' => $sizes,
            'filters' => $filters
        ];
    }
    
    /**
     * Get filtered leather shoes
     * 
     * @return array Filtered leather shoes
     */
    public function getFilteredLeatherShoes() {
        $filters = $this->filterModel->parseFilterParams();
        $sizes = $this->filterModel->getAvailableSizes();
        
        $leatherShoes = $this->productModel->getLeatherShoes($filters);
        
        return [
            'products' => $leatherShoes,
            'sizes' => $sizes,
            'filters' => $filters
        ];
    }
    
    /**
     * Get filtered children shoes
     * 
     * @return array Filtered children shoes
     */
    public function getFilteredChildrenShoes() {
        $filters = $this->filterModel->parseFilterParams();
        $sizes = $this->filterModel->getAvailableSizes();
        
        $childrenShoes = $this->productModel->getChildrenShoes($filters);
        
        return [
            'products' => $childrenShoes,
            'sizes' => $sizes,
            'filters' => $filters
        ];
    }
} 