<?php
session_start();
require_once '../db/connect.php';
require_once '../models/product.php';
require_once '../models/category.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../login.php");
    exit();
}

$error = '';
$success = '';
$categories = getAllCategories($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $category_id = (int)$_POST['category_id'];
    
    
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
            'category_id' => $category_id,
            'image_url' => ''
        ];

        
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image_name = uploadProductImage($_FILES['image']);
            if ($image_name) {
                $data['image_url'] = $image_name;
            } else {
                $error = "Lỗi upload ảnh. Vui lòng thử lại!";
            }
        }

        
        if (empty($error) && addProduct($conn, $data)) {
            header("Location: products.php?message=Thêm sản phẩm thành công!");
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
    <title>Thêm Sản Phẩm - Admin</title>
    <link rel="stylesheet" href="../css/admin/dashboard.css">
    <link rel="stylesheet" href="../css/admin/products.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'includes/sidebar.php'; ?>

        <div class="main-content">
            <header class="top-bar">
                <h1>Thêm Sản Phẩm</h1>
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
                               value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="description">Mô Tả:</label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="4"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="price">Giá:</label>
                        <input type="number" 
                               id="price" 
                               name="price" 
                               required 
                               min="0"
                               value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="category_id">Danh Mục:</label>
                        <select id="category_id" name="category_id" required>
                            <option value="">Chọn danh mục</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>"
                                    <?php echo (isset($_POST['category_id']) && $_POST['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="image">Hình Ảnh:</label>
                        <input type="file" 
                               id="image" 
                               name="image" 
                               accept="image/*">
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
