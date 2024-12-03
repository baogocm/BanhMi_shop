<?php
session_start();  // Khởi tạo session

// Xóa tất cả các biến session
session_unset();

// Hủy session
session_destroy();

// Đặt thông báo đăng xuất thành công vào session
$_SESSION['logout_success'] = true;

// Chuyển hướng về trang chủ sau khi đăng xuất
header("Location: index.php");
exit();
?>
