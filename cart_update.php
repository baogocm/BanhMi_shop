<?php
session_start();
require_once 'db/connect.php';
require_once 'models/cart.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Vui lòng đăng nhập!'
    ]);
    exit;
}

// Đọc dữ liệu JSON từ request
$data = json_decode(file_get_contents('php://input'), true);
$product_id = $data['product_id'] ?? 0;
$change = $data['change'] ?? 0;

$cart = new Cart($conn);

// Cập nhật số lượng
if ($cart->updateQuantity($_SESSION['user_id'], $product_id, $change)) {
    // Lấy tổng số tiền mới
    $total = $cart->calculateTotal($_SESSION['user_id']);
    $cartCount = $cart->getCartItemCount($_SESSION['user_id']);

    echo json_encode([
        'success' => true,
        'total' => $total,
        'cartCount' => $cartCount,
        'message' => 'Cập nhật thành công'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Không thể cập nhật số lượng'
    ]);
} 