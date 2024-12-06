<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra đăng nhập
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Kiểm tra quyền Admin
function checkAdmin() {
    if (!isLoggedIn() || $_SESSION['role'] != 1) {
        // Nếu không phải admin, chuyển hướng tới login
        header("Location: login.php");
        exit();
    }
}

// Kiểm tra quyền Khách hàng
function checkCustomer() {
    if (!isLoggedIn() || $_SESSION['role'] == 1) {
        // Nếu là admin hoặc chưa đăng nhập, chuyển hướng tới login
        header("Location: login.php");
        exit();
    }
}
?>
