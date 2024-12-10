<?php
session_start();
require_once 'db/connect.php';
require_once 'models/Cart.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? null;
    $productId = $data['productId'] ?? null;
    $quantity = $data['quantity'] ?? 1;

    $cartModel = new Cart($conn);

    if ($action === 'add') {
        if ($cartModel->addToCart($productId, $quantity)) {
            echo json_encode(['success' => true, 'message' => 'Sản phẩm đã được thêm vào giỏ hàng.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể thêm sản phẩm.']);
        }
    } elseif ($action === 'remove') {
        if ($cartModel->removeFromCart($productId)) {
            echo json_encode(['success' => true, 'message' => 'Sản phẩm đã được xóa.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể xóa sản phẩm.']);
        }
    } elseif ($action === 'increase') {
        if ($cartModel->increaseQuantity($productId)) {
            echo json_encode(['success' => true, 'message' => 'Tăng số lượng thành công.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể tăng số lượng.']);
        }
    } elseif ($action === 'decrease') {
        if ($cartModel->decreaseQuantity($productId)) {
            echo json_encode(['success' => true, 'message' => 'Giảm số lượng thành công.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể giảm số lượng.']);
        }
    }
}
?>
