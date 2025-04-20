<?php
// Initialize session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
$isLoggedIn = isset($_SESSION['id_user']) && !empty($_SESSION['id_user']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
?>

<nav class="colorlib-nav" role="navigation">
    <div class="top-menu">
        <div class="container">
            <div class="row">
                <div class="col-sm-7 col-md-9">
                    <div id="colorlib-logo"><a href="index.php">Footwear</a></div>
                </div>
                <div class="col-sm-5 col-md-3">
                    <form action="#" class="search-wrap">
                       <div class="form-group">
                          <input type="search" class="form-control search" placeholder="Search">
                          <button class="btn btn-primary submit-search text-center" type="submit"><i class="icon-search"></i></button>
                       </div>
                    </form>
                 </div>
             </div>
            <div class="row">
                <div class="col-sm-12 text-left menu-1">
                    <ul>
                        <li class="active"><a href="index.php">Home</a></li>
                        <li><a href="/web_php_mvc/View/user/sneaker-shoes.php">Sneakers</a></li>
                        <li><a href="/web_php_mvc/View/user/leather-shoes.php">Giày da</a></li>
                        <li><a href="/web_php_mvc/View/user/children-shoes.php">Giày trẻ em</a></li>
                        <li class="cart"><a href="/web_php_mvc/View/user/cart.php"><i class="icon-shopping-cart"></i> Cart [0]</a></li>
                        <li class="cart">
                            <?php if ($isLoggedIn): ?>
                                <a href="/web_php_mvc/View/user/profile.php"><i class="icon-user"></i> <?php echo htmlspecialchars($username); ?></a>
                            <?php else: ?>
                                <a href="/web_php_mvc/View/auth/login.php"><i class="icon-user"></i> Login</a>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="sale">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 offset-sm-2 text-center">
                    <div class="row">
                        <div class="owl-carousel2">
                            <div class="item">
                                <div class="col">
                                    <h3><a href="#">25% off (Almost) Everything! Use Code: Summer Sale</a></h3>
                                </div>
                            </div>
                            <div class="item">
                                <div class="col">
                                    <h3><a href="#">Our biggest sale yet 50% off all summer shoes</a></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav> 