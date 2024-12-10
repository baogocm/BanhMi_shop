<?php
require_once 'models/user.php';
require_once 'db/connect.php';

session_start();

// Khởi tạo đối tượng User
$userModel = new User($conn);

// Xử lý đăng xuất
if ($userModel->logout()) {
    // Tạo session mới để lưu thông báo
    session_start();
    $_SESSION['message'] = "Đăng xuất thành công!";
}

// Chuyển hướng về trang đăng nhập
header("Location: index.php");
exit();
?>
