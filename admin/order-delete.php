<?php
session_start();
require_once '../db/connect.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../login.php");
    exit();
}


if (isset($_GET['id'])) {
    $order_id = $_GET['id'];
    
    try {
        
        $conn->beginTransaction();

        
        $sql = "DELETE FROM order_items WHERE order_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$order_id]);

        
        $sql = "DELETE FROM orders WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$order_id]);

        
        $conn->commit();

        
        header("Location: orders.php?message=Xóa đơn hàng thành công!");
        exit();
    } catch (PDOException $e) {
        
        $conn->rollBack();
        
        
        header("Location: orders.php?error=Lỗi khi xóa đơn hàng: " . urlencode($e->getMessage()));
        exit();
    }
} else {
    
    header("Location: orders.php?error=Không tìm thấy đơn hàng cần xóa!");
    exit();
}
?> 