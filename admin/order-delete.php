<?php
session_start();
require_once '../db/connect.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../login.php");
    exit();
}

// Kiểm tra có id đơn hàng cần xóa
if (isset($_GET['id'])) {
    $order_id = $_GET['id'];
    
    try {
        // Bắt đầu transaction
        $conn->beginTransaction();

        // Xóa chi tiết đơn hàng từ bảng order_items
        $sql = "DELETE FROM order_items WHERE order_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$order_id]);

        // Xóa đơn hàng
        $sql = "DELETE FROM orders WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$order_id]);

        // Commit transaction
        $conn->commit();

        // Chuyển hướng về trang orders với thông báo thành công
        header("Location: orders.php?message=Xóa đơn hàng thành công!");
        exit();
    } catch (PDOException $e) {
        // Rollback nếu có lỗi
        $conn->rollBack();
        
        // Chuyển hướng về trang orders với th��ng báo lỗi
        header("Location: orders.php?error=Lỗi khi xóa đơn hàng: " . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Nếu không có id đơn hàng
    header("Location: orders.php?error=Không tìm thấy đơn hàng cần xóa!");
    exit();
}
?> 