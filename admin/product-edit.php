<?php
session_start();
require_once '../db/connect.php';
require_once '../models/product.php';
require_once '../models/category.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../login.php");
    exit();
}

// Kiểm tra ID
if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$product_id = (int)$_GET['id'];
$product = getProductById($conn, $product_id);
$categories = getAllCategories($conn);

// Nếu không tìm thấy sản phẩm
if (!$product) {
    header("Location: products.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $category_id = (int)$_POST['category_id'];
    
    // Validate
    if (empty($name)) {
        $error = "Vui lòng nhập tên sản phẩm";
    } elseif (empty($price)) {
        $error = "Vui lòng nhập giá sản phẩm";
    } elseif ($price <= 0) {
        $error = "Giá sản phẩm phải lớn hơn 0";
    } elseif (empty($category_id)) {
        $error = "Vui lòng chọn danh mục";
    } else {
        $data = [
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'category_id' => $category_id
        ];

        // Xử lý upload ảnh mới nếu có
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image_name = uploadProductImage($_FILES['image']);
            if ($image_name) {
                $data['image_url'] = $image_name;
                
                // Xóa ảnh cũ
                if ($product['image_url']) {
                    $old_image = "../images/" . $product['image_url'];
                    if (file_exists($old_image)) {
                        unlink($old_image);
                    }
                }
            } else {
                $error = "Lỗi upload ảnh. Vui lòng thử lại!";
            }
        }

        // Cập nhật sản phẩm
        if (empty($error) && updateProduct($conn, $product_id, $data)) {
            header("Location: products.php?message=Cập nhật sản phẩm thành công!");
            exit();
        } else {
            $error = "Có lỗi xảy ra, vui lòng thử lại!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Sản Phẩm - Admin</title>
    <link rel="stylesheet" href="../css/admin/dashboard.css">
    <link rel="stylesheet" href="../css/admin/products.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'includes/sidebar.php'; ?>

        <div class="main-content">
            <header class="top-bar">
                <h1>Sửa Sản Phẩm</h1>
                <a href="products.php" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </header>

            <div class="content-box">
                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="form" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Tên Sản Phẩm:</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               required 
                               value="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="description">Mô Tả:</label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="4"><?php echo htmlspecialchars($product['description']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="price">Giá:</label>
                        <input type="number" 
                               id="price" 
                               name="price" 
                               required 
                               min="0"
                               value="<?php echo htmlspecialchars($product['price']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="category_id">Danh Mục:</label>
                        <select id="category_id" name="category_id" required>
                            <option value="">Chọn danh mục</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>"
                                    <?php echo ($product['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="image">Hình Ảnh:</label>
                        <?php if ($product['image_url']): ?>
                            <div class="current-image">
                                <img src="../images/<?php echo htmlspecialchars($product['image_url']); ?>" 
                                     alt="Current Image" 
                                     style="max-width: 200px;">
                            </div>
                        <?php endif; ?>
                        <input type="file" 
                               id="image" 
                               name="image" 
                               accept="image/*">
                        <small>Để trống nếu không muốn thay đổi ảnh</small>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Lưu
                        </button>
                        <a href="products.php" class="btn-cancel">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html> 