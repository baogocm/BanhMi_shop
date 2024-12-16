<?php
require_once 'db/connect.php';
require_once 'includes/header.php';

if (isset($_GET['query'])) {
    $query = $_GET['query'];

    // Sử dụng Prepared Statements với PDO
    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE :query");
    $searchTerm = "%" . $query . "%";
    $stmt->bindParam(':query', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<div class='container mt-5'>";
    echo "<h2 class='section-title'>Kết quả tìm kiếm cho: '$query'</h2>";

    if (count($result) > 0) {
        echo "<div class='products-grid'>"; // Sử dụng class có sẵn
        foreach ($result as $product) {
            echo "<div class='product-card'>"; // Sử dụng class có sẵn
            echo "<div class='product-image-container'>"; // Sử dụng class có sẵn
            echo "<img src='images/" . htmlspecialchars($product['image_url']) . "' alt='" . htmlspecialchars($product['name']) . "' class='product-image'>"; // Sử dụng class có sẵn
            echo "</div>";
            echo "<div class='product-info'>"; // Sử dụng class có sẵn
            echo "<div class='product-text'>"; // Sử dụng class có sẵn
            echo "<h3 class='product-title'>" . htmlspecialchars($product['name']) . "</h3>"; // Sử dụng class có sẵn
            echo "<p class='product-description'>" . htmlspecialchars(substr($product['description'], 0, 40)) . "...</p>"; // Sử dụng class có sẵn
            echo "</div>";
            echo "<div class='product-bottom'>"; // Sử dụng class có sẵn
            echo "<div class='product-price'>" . number_format($product['price'], 0, ',', '.') . " VND</div>"; // Sử dụng class có sẵn
            echo "<button class='btn-add-cart' data-product-id='" . $product['id'] . "'>Thêm vào giỏ hàng</button>"; // Sử dụng class có sẵn
            echo "</div>";
            echo "</div></div>";
        }
        echo "</div>";
    } else {
        echo "<p>Không tìm thấy sản phẩm nào.</p>";
    }

    echo "</div>";
} else {
    echo "<p>Vui lòng nhập từ khóa tìm kiếm.</p>";
}

require_once 'includes/footer.php';
?>
