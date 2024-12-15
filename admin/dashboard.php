<?php
session_start();
require_once '../db/connect.php';

// Kiểm tra đăng nhập và role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../login.php");
    exit();
}

// Lấy thống kê cơ bản
$stats = [
    'total_products' => $conn->query("SELECT COUNT(*) FROM products")->fetchColumn(),
    'total_categories' => $conn->query("SELECT COUNT(*) FROM categories")->fetchColumn(),
    'total_orders' => $conn->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
    'total_users' => $conn->query("SELECT COUNT(*) FROM users")->fetchColumn()
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Bánh Mì Shop</title>
    <link rel="stylesheet" href="../css/admin/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <h2>Admin Panel</h2>
            </div>
            <nav class="nav-menu">
                <a href="dashboard.php" class="active">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="products.php">
                    <i class="fas fa-bread-slice"></i> Sản Phẩm
                </a>
                <a href="categories.php">
                    <i class="fas fa-list"></i> Danh Mục
                </a>
                <a href="orders.php">
                    <i class="fas fa-shopping-cart"></i> Đơn Hàng
                </a>
                <a href="users.php">
                    <i class="fas fa-users"></i> Người Dùng
                </a>
                <a href="../index.php">
                    <i class="fas fa-home"></i> Trang Bán Hàng
                </a>
                <a href="../logout.php">
                    <i class="fas fa-sign-out-alt"></i> Đăng Xuất
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <header class="top-bar">
                <h1>Dashboard</h1>
                <div class="user-info">
                    <span>Xin chào, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </div>
            </header>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <i class="fas fa-bread-slice"></i>
                    <div class="stat-info">
                        <h3>Sản Phẩm</h3>
                        <p><?php echo $stats['total_products']; ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-list"></i>
                    <div class="stat-info">
                        <h3>Danh Mục</h3>
                        <p><?php echo $stats['total_categories']; ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-shopping-cart"></i>
                    <div class="stat-info">
                        <h3>Đơn Hàng</h3>
                        <p><?php echo $stats['total_orders']; ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-users"></i>
                    <div class="stat-info">
                        <h3>Người Dùng</h3>
                        <p><?php echo $stats['total_users']; ?></p>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="recent-orders">
                <h2>Đơn Hàng Gần Đây</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Khách Hàng</th>
                            <th>Sản Phẩm</th>
                            <th>Tổng Tiền</th>
                            <th>Trạng Thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Thêm dữ liệu đơn hàng từ database -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
