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
$product_id = $data['productId'] ?? 0;
$quantity = $data['quantity'] ?? 1;

$cart = new Cart($conn);

// Thêm vào giỏ hàng
if ($cart->addItem($_SESSION['user_id'], $product_id, $quantity)) {
    // Lấy thông tin giỏ hàng mới
    $cartItems = $cart->getCartItems($_SESSION['user_id']);
    $total = $cart->calculateTotal($_SESSION['user_id']);
    $cartCount = $cart->getCartItemCount($_SESSION['user_id']);

    echo json_encode([
        'success' => true,
        'cart' => $cartItems,
        'total' => $total,
        'cartCount' => $cartCount,
        'message' => 'Đã thêm vào giỏ hàng'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Không thể thêm vào giỏ hàng'
    ]);
}
