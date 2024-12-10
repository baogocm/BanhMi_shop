<?php
session_start();
require_once '../db/connect.php';
require_once '../models/product.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../login.php");
    exit();
}

// Xử lý xóa sản phẩm nếu có id được gửi đến
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    if (deleteProduct($conn, $id)) {
        header("Location: products.php?msg=deleted");
        exit();
    }
}

// Phân trang
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Lấy tổng số sản phẩm và danh sách sản phẩm
$totalProducts = getTotalProducts($conn);
$totalPages = ceil($totalProducts / $limit);
$products = getProductsPaginated($conn, $limit, $offset);

// Thêm thông tin danh mục
$productsWithCategories = array_map(function($product) use ($conn) {
    $sql = "SELECT name FROM categories WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$product['category_id']]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);
    $product['category_name'] = $category ? $category['name'] : 'Không có danh mục';
    return $product;
}, $products);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Sản Phẩm - Admin</title>
    <link rel="stylesheet" href="../css/admin/dashboard.css">
    <link rel="stylesheet" href="../css/admin/products.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'includes/sidebar.php'; ?>

        <div class="main-content">
            <header class="top-bar">
                <h1>Quản Lý Sản Phẩm</h1>
                <a href="product-add.php" class="btn-add">
                    <i class="fas fa-plus"></i> Thêm Sản Phẩm
                </a>
            </header>

            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-success">
                Sản phẩm đã được xóa thành công!
            </div>
            <?php endif; ?>

            <div class="content-box">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Hình Ảnh</th>
                            <th>Tên Sản Phẩm</th>
                            <th>Mô tả</th>
                            <th>Danh Mục</th>
                            <th>Giá</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productsWithCategories as $product): ?>
                        <tr>
                            <td><?php echo $product['id']; ?></td>
                            <td>
                                <img src='../images/<?php echo $product['image_url']; ?>' 
                                     alt='<?php echo htmlspecialchars($product['name']); ?>' 
                                     class="product-thumb"
                                     onerror="this.src='../images/default.jpg'">
                            </td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td>
                                <?php 
                                    $description = $product['description'];
                                    echo strlen($description) > 50 ? substr($description, 0, 50) . '...' : $description;
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                            <td><?php echo number_format($product['price'], 0, ',', '.'); ?>đ</td>
                            <td class="actions">
                                <a href="product-edit.php?id=<?php echo $product['id']; ?>" class="btn-edit" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="?delete_id=<?php echo $product['id']; ?>" 
                                   class="btn-delete" 
                                   onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')"
                                   title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Phân trang -->
                <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>" 
                           class="page-link <?php echo $page == $i ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
                        