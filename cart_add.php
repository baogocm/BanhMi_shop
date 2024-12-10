<?php
session_start();
require_once 'db/connect.php';
require_once 'models/Cart.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'];
    $productId = $data['productId'];

    // Khởi tạo model Cart
    $cartModel = new Cart($conn);

    if ($action === 'increase') {
        if ($cartModel->increaseQuantity($productId)) {
            echo json_encode(['success' => true, 'message' => 'Số lượng sản phẩm đã tăng lên.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể tăng số lượng sản phẩm.']);
        }
    } elseif ($action === 'decrease') {
        if ($cartModel->decreaseQuantity($productId)) {
            echo json_encode(['success' => true, 'message' => 'Số lượng sản phẩm đã giảm!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể giảm số lượng sản phẩm.']);
        }
    } elseif ($action === 'remove') {
        if ($cartModel->removeFromCart($productId)) {
            echo json_encode(['success' => true, 'message' => 'Sản phẩm đã được xóa khỏi giỏ hàng.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể xóa sản phẩm khỏi giỏ hàng.']);
        }
    }
}
?>
