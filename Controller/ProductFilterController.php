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
    
    public function getFilteredSneakers() {
        $filters = $this->filterModel->parseFilterParams();
        $sizes = $this->filterModel->getAvailableSizes();
        
        $result = $this->productModel->getSneakerShoes($filters);
        $paginationHtml = $this->productModel->buildPaginationLinks($result['pagination'], $filters);
        
        return [
            'products' => $result['products'],
            'sizes' => $sizes,
            'filters' => $filters,
            'pagination' => $result['pagination'],
            'paginationHtml' => $paginationHtml
        ];
    }
    
    public function getFilteredLeatherShoes() {
        $filters = $this->filterModel->parseFilterParams();
        $sizes = $this->filterModel->getAvailableSizes();
        
        $result = $this->productModel->getLeatherShoes($filters);
        $paginationHtml = $this->productModel->buildPaginationLinks($result['pagination'], $filters);
        
        return [
            'products' => $result['products'],
            'sizes' => $sizes,
            'filters' => $filters,
            'pagination' => $result['pagination'],
            'paginationHtml' => $paginationHtml
        ];
    }
    

    public function getFilteredChildrenShoes() {
        $filters = $this->filterModel->parseFilterParams();
        $sizes = $this->filterModel->getAvailableSizes();
        
        $result = $this->productModel->getChildrenShoes($filters);
        $paginationHtml = $this->productModel->buildPaginationLinks($result['pagination'], $filters);
        
        return [
            'products' => $result['products'],
            'sizes' => $sizes,
            'filters' => $filters,
            'pagination' => $result['pagination'],
            'paginationHtml' => $paginationHtml
        ];
    }
} 