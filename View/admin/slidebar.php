 <div class="sidebar">
    <a href="index.php"><i class="fas fa-tachometer-alt"></i></a>
    <a href="users.php" class="<?php echo ($activePage == 'users') ? 'active' : ''; ?>"><i class="fas fa-users"></i></a>
    <a href="products.php" class="<?php echo ($activePage == 'products') ? 'active' : ''; ?>"><i class="fas fa-shoe-prints"></i></a>
    <a href="product_variants.php" class="<?php echo ($activePage == 'variants') ? 'active' : ''; ?>"><i class="fas fa-cubes"></i></a>
    <a href="promotions.php" class="<?php echo ($activePage == 'promotions') ? 'active' : ''; ?>" title="Quản lý khuyến mãi"><i class="fas fa-gift"></i></a>
    <a href="invoice.php" class="<?php echo ($activePage == 'invoice') ? 'active' : ''; ?>"><i class="fas fa-file-invoice"></i></a>
    <a href="/web_php_mvc/View/admin/warranty.php" class="sidebar-link"> <i class="fas fa-tools"></i></a></a>
       
    <a href="role.php?id=1" class="<?php echo ($activePage == 'role') ? 'active' : ''; ?>"><i class="fa fa-ravelry"></i></a>
    <a href="statistics.php" class="<?php echo ($activePage == 'statistics') ? 'active' : ''; ?>"><i class="fas fa-chart-bar"></i></a>

    <a href="../admin/logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i></a>
</div>
