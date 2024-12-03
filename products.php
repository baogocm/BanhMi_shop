<?php
require_once 'db/connect.php';
require_once 'includes/header.php';
require_once 'models/product.php';
require_once 'models/category.php';

// Lấy category_id từ URL nếu có
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;

// Thiết lập phân trang
$limit = 6; // Số sản phẩm mỗi trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Lấy tổng số sản phẩm dựa vào category
if ($category_id) {
    $total_products = count(getProductsByCategory($conn, $category_id));
    $products = getProductsByCategoryPaginated($conn, $category_id, $limit, $offset);
} else {
    $total_products = count(getAllProducts($conn));
    $products = getProductsPaginated($conn, $limit, $offset);
}

$total_pages = ceil($total_products / $limit);

// Lấy tất cả danh mục
$categories = getAllCategories($conn);
?>

<div class="products-page">
    <div class="products-header">
        <h1>Sản Phẩm</h1>
    </div>

    <div class="products-container">
        <!-- Dropdown Sidebar -->
        <div class="dropdown-sidebar">
            <select class="form-select category-dropdown" onchange="window.location.href=this.value">
                <option value="products.php" <?php echo !$category_id ? 'selected' : ''; ?>>
                    Tất cả sản phẩm
                </option>
                <?php foreach ($categories as $category): ?>
                <option value="products.php?category=<?php echo $category['id']; ?>"
                        <?php echo $category_id == $category['id'] ? 'selected' : ''; ?>>
                    <?php echo $category['name']; ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Products Grid -->
        <div class="products-content">
            <div class="products-grid">
                <?php if (count($products) > 0): ?>
                    <?php foreach ($products as $product): ?>
                        <div class='product-card'>
                            <img src='images/<?php echo $product['image_url']; ?>' 
                                alt='<?php echo $product['name']; ?>' class='product-image'>
                            <div class='product-info'>
                                <div class="product-details">
                                    <h3 class='product-title'><?php echo $product['name']; ?></h3>
                                    <p class='product-description text-truncate'><?php echo $product['description']; ?></p>
                                    <div class='product-price'><?php echo number_format($product['price'], 0, ',', '.'); ?> VND</div>
                                </div>
                                <div class="product-action">
                                    <button class='btn-add-cart w-100'><i class='fas fa-shopping-cart'></i> Thêm vào giỏ hàng</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-products">Không có sản phẩm nào trong danh mục này.</p>
                <?php endif; ?>
            </div>

            <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="products.php?<?php echo $category_id ? "category=$category_id&" : ''; ?>page=<?php echo $i; ?>" 
                       class="page-link <?php echo $i == $page ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
