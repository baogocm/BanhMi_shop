<div class="sidebar">
    <div class="logo">
        <h2>Admin Panel</h2>
    </div>
    <nav class="nav-menu">
        <a href="dashboard.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'class="active"' : ''; ?>>
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="products.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'products.php') ? 'class="active"' : ''; ?>>
            <i class="fas fa-bread-slice"></i> Sản Phẩm
        </a>
        <a href="categories.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'categories.php') ? 'class="active"' : ''; ?>>
            <i class="fas fa-list"></i> Danh Mục
        </a>
        <a href="orders.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'orders.php') ? 'class="active"' : ''; ?>>
            <i class="fas fa-shopping-cart"></i> Đơn Hàng
        </a>
        <a href="users.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'users.php') ? 'class="active"' : ''; ?>>
            <i class="fas fa-users"></i> Người Dùng
        </a>
        <a href="../logout.php">
            <i class="fas fa-sign-out-alt"></i> Đăng Xuất
        </a>
    </nav>
</div>
