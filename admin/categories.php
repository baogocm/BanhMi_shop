<?php
session_start();
require_once '../db/connect.php';
require_once '../models/category.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../login.php");
    exit();
}

// Xử lý xóa danh mục nếu có request
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $category_id = (int)$_GET['id'];
    if (deleteCategory($conn, $category_id)) {
        header("Location: categories.php");
    } else {
        $error = "Không thể xóa danh mục này!";
    }
}

// Lấy danh sách danh mục
$categories = getAllCategories($conn);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Danh Mục - Admin</title>
    <link rel="stylesheet" href="../css/admin/dashboard.css">
    <link rel="stylesheet" href="../css/admin/categories.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'includes/sidebar.php'; ?>

        <div class="main-content">
            <header class="top-bar">
                <h1>Quản Lý Danh Mục</h1>
                <a href="category-add.php" class="btn-add">
                    <i class="fas fa-plus"></i> Thêm Danh Mục
                </a>
            </header>

            <div class="content-box">
                <?php if (isset($error)): ?>
                    <div class="alert alert-error">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên Danh Mục</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?php echo $category['id']; ?></td>
                            <td><?php echo htmlspecialchars($category['name']); ?></td>
                            <td class="actions">
                                <a href="category-edit.php?id=<?php echo $category['id']; ?>" 
                                   class="btn-edit" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="?action=delete&id=<?php echo $category['id']; ?>" 
                                   class="btn-delete" 
                                   onclick="return confirm('Bạn có chắc muốn xóa danh mục này?')"
                                   title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
