<?php
// Routes for the application

// Home page
$router->get('/', 'ProductController@index');

// Product detail page
$router->get('/product-detail/{id}', 'ProductController@productDetail');

// Categories
$router->get('/sneaker-shoes', 'ProductController@sneakerShoes');
$router->get('/leather-shoes', 'ProductController@leatherShoes');
$router->get('/children-shoes', 'ProductController@childrenShoes');

// User authentication
$router->get('/login', 'AuthController@showLoginForm');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@showRegisterForm');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');

// Cart and checkout
$router->get('/cart', 'CartController@index');
$router->post('/add-to-cart', 'CartController@addToCart');
$router->get('/checkout', 'CartController@checkout');
$router->post('/place-order', 'CartController@placeOrder');

// User profile
$router->get('/profile', 'UserProfileController@index');
$router->post('/update-profile', 'UserProfileController@update');
$router->post('/add-address', 'UserProfileController@addAddress');
?>
