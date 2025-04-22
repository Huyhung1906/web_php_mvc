<?php
require_once __DIR__ . '/Controller/CartController.php';

// Initialize the cart controller
$cartController = new CartController();

// Handle the request
$cartController->handleAjaxRequest();
?> 