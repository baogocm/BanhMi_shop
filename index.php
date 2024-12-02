<?php
require './connect.php';
$sql = "SELECT * FROM products"; // Bước 2
$stm = $conn->prepare($sql); 
$stm->execute([]); 
$n = $stm->rowCount(); 
$data = $stm->fetchAll(PDO::FETCH_ASSOC); // data là mảng 2 chiều
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bánh Mì Shop</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="asset/bootstrap.min.css">
    <link rel="stylesheet" href="asset/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">🥖 Bánh Mì Shop</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-home"></i> Trang Chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-info-circle"></i> Giới Thiệu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-shopping-bag"></i> Sản Phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-phone"></i> Liên Hệ</a>
                    </li>
                    <li class="nav-item search-bar">
                        <input type="text" class="search-input" placeholder="Tìm kiếm...">
                    </li>
                    <li class="nav-item">
                        <a class="nav-link user-icon" href="#"><i class="fas fa-user"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>Danh Mục Sản Phẩm</h2>
            <ul class="category-list">
                <li class="category-item">
                    <a href="index.php" style="text-decoration: none; color: inherit;">
                        <i class="fas fa-bread-slice"></i> Tất cả sản phẩm
                    </a>
                </li>
                <?php
                require './connect.php';
                $sql = "SELECT * FROM categories";
                $stm = $conn->prepare($sql);
                $stm->execute();
                $categories = $stm->fetchAll();

                foreach ($categories as $category) {
                    echo "<li class='category-item'>";
                    echo "<a href='index.php?category=" . $category['id'] . "' style='text-decoration: none; color: inherit;'>";
                    echo "<i class='fas fa-bread-slice'></i> " . $category['name'];
                    echo "</a></li>";
                }
                ?>
            </ul>
        </div>

        <!-- Products Grid -->
        <div class="products-container">
            <div class="products-grid">
                <?php
                if(isset($_GET['category'])) {
                    $category_id = $_GET['category'];
                    $sql = "SELECT * FROM products WHERE id_category = ?";
                    $stm = $conn->prepare($sql);
                    $stm->execute([$category_id]);
                } else {
                    $sql = "SELECT * FROM products";
                    $stm = $conn->prepare($sql);
                    $stm->execute();
                }
                
                $products = $stm->fetchAll();
                
                foreach ($products as $product) {
                    echo "<div class='product-card'>";
                    echo "<img src='asset/images/" . $product['image_url'] . "' 
                          alt='" . $product['name'] . "' class='product-image'>";
                    echo "<div class='product-info'>";
                    echo "<h3 class='product-title'>" . $product['name'] . "</h3>";
                    echo "<p class='product-description'>" . $product['description'] . "</p>";
                    echo "<div class='product-price'>" . number_format($product['price'], 0, ',', '.') . " VND</div>";
                    echo "<button class='btn-add-cart'><i class='fas fa-shopping-cart'></i> Thêm vào giỏ hàng</button>";
                    echo "</div></div>";
                }
                ?>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2023 Bánh Mì Shop. All rights reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>