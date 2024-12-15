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

$cart = new Cart($conn);

// Xóa sản phẩm khỏi giỏ hàng
if ($cart->removeItem($_SESSION['user_id'], $product_id)) {
    // Lấy tổng số tiền mới
    $total = $cart->calculateTotal($_SESSION['user_id']);
    $cartCount = $cart->getCartItemCount($_SESSION['user_id']);

    echo json_encode([
        'success' => true,
        'total' => $total,
        'cartCount' => $cartCount,
        'message' => 'Đã xóa sản phẩm khỏi giỏ hàng'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Không thể xóa sản phẩm'
    ]);
}

