<?php
session_start();
require_once '../db/connect.php';
require_once '../models/category.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../login.php");
    exit();
}


if (!isset($_GET['id'])) {
    header("Location: categories.php");
    exit();
}

$category_id = (int)$_GET['id'];
$category = getCategoryById($conn, $category_id);


if (!$category) {
    header("Location: categories.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    

    if (empty($name)) {
        $error = "Vui lòng nhập tên danh mục";
    } else {

        if (updateCategory($conn, $category_id, $name)) {
            header("Location: categories.php?message=Cập nhật danh mục thành công!");
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
    <title>Sửa Danh Mục - Admin</title>
    <link rel="stylesheet" href="../css/admin/dashboard.css">
    <link rel="stylesheet" href="../css/admin/categories.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'includes/sidebar.php'; ?>

        <div class="main-content">
            <header class="top-bar">
                <h1>Sửa Danh Mục</h1>
                <a href="categories.php" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </header>

            <div class="content-box">
                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="form">
                    <div class="form-group">
                        <label for="name">Tên Danh Mục:</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               required 
                               value="<?php echo htmlspecialchars($category['name']); ?>"
                               placeholder="Nhập tên danh mục">
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Lưu
                        </button>
                        <a href="categories.php" class="btn-cancel">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
