<?php
require_once 'db/connect.php';
require_once 'models/User.php'; 

session_start();

if (isset($_SESSION['login_success']) && $_SESSION['login_success'] == true) {
    echo "<script>
        toastr.success('Đăng nhập thành công!');
    </script>";
    unset($_SESSION['login_success']);  // Xóa thông báo login thành công
}

// Kiểm tra nếu đăng nhập thành công
if (isset($_SESSION['login_success']) && $_SESSION['login_success'] == true) {
    echo "<script>
        toastr.success('Đăng nhập thành công!');
    </script>";
    unset($_SESSION['login_success']);  // Xóa thông báo sau khi đã hiển thị
}

// Tự động load CSS dựa trên tên file PHP hiện tại
$current_page = basename($_SERVER['PHP_SELF'], '.php');
if (file_exists("css/pages/{$current_page}.css")) {
    echo "<link rel='stylesheet' href='css/pages/{$current_page}.css'>";
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bánh Mì Shop</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php">🥖 Bánh Mì Shop</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <form class="form-inline my-2 my-lg-0 mr-auto">
                    <div class="input-group">
                        <input class="form-control" type="search" placeholder="Tìm kiếm..." aria-label="Search">
                        <div class="input-group-append">
                            <button class="search-btn" type="submit">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                        </div>
                    </div>
                </form>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="fas fa-home"></i> Trang Chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php"><i class="fas fa-info-circle"></i> Giới Thiệu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php"><i class="fas fa-shopping-bag"></i> Sản Phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php"><i class="fas fa-phone"></i> Liên Hệ</a>
                    </li>
                    <li class="nav-item d-flex">
                        <?php if (isset($_SESSION['username'])): ?>
                            <a class="nav-link me-2" href="#">
                                <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['username']); ?>
                            </a>
                            <a class="nav-link" href="logout.php">
                                <i class="fas fa-sign-out-alt"></i> Đăng xuất
                            </a>
                        <?php else: ?>
                            <a class="nav-link" href="login.php">
                                <i class="fas fa-user"></i> Đăng nhập
                            </a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</body>
</html>
