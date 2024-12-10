<?php
require_once 'db/connect.php';
require_once 'includes/header.php';
require_once 'models/category.php';
require_once 'models/product.php';
require_once 'models/User.php';
?>


<!-- Banner Section -->
<div class="banner-section">
    <div class="banner-container">
        <img src="images/banner.jpg" alt="Banner" class="banner-image">
        <div class="banner-content">
            <h1>Bánh Mì Shop</h1>
            <p>Nơi mang đến cho bạn những ổ bánh mì thơm ngon nhất</p>
        </div>
    </div>
</div>

<!-- Categories Section -->
<div class="categories-section">
    <div class="container">
        <h2 class="section-title">Danh Mục Sản Phẩm</h2>
        <div class="categories-grid">
            <?php
            $categories = getAllCategories($conn);
            foreach ($categories as $category) {
                echo "<div class='category-card'>";
                echo "<div class='category-icon'><i class='fas fa-bread-slice'></i></div>";
                echo "<h3>" . $category['name'] . "</h3>";
                echo "<a href='products.php?category=" . $category['id'] . "' class='category-link'>Xem Sản Phẩm</a>";
                echo "</div>";
            }
            ?>
        </div>
    </div>
</div>

<!-- Featured Products Section -->
<div class="featured-products">
    <div class="container">
        
        <h2 class="section-title">Sản Phẩm Nổi Bật</h2>
        <div class="products-grid">
            <?php
            $products = getLatestProducts($conn, 4);
            foreach ($products as $product) {
                echo "<div class='product-card'>";
                echo "<div class='product-image-container'>";
                echo "<img src='images/" . $product['image_url'] . "' alt='" . $product['name'] . "' class='product-image'>";
                echo "</div>";
                echo "<div class='product-info'>";
                echo "<div class='product-text'>";
                echo "<h3 class='product-title'>" . $product['name'] . "</h3>";
                echo "<p class='product-description'>" . substr($product['description'], 0, 40) . "...</p>";
                echo "</div>";
                echo "<div class='product-bottom'>";
                echo "<div class='product-price'>" . number_format($product['price'], 0, ',', '.') . " VND</div>";
                echo "<button class='btn-add-cart' data-product-id='" . $product['id'] . "'><i class='fas fa-shopping-cart'></i> Thêm vào giỏ hàng</button>";
                echo "</div>";
                echo "</div></div>";
            }
            ?>
        </div>
        <a href="products.php" class="btn-view-all">Xem Tất Cả Sản Phẩm</a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>