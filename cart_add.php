<?php
session_start();
require_once 'db/connect.php';
require_once 'models/cart.php';

// Kiểm tra nếu yêu cầu được gửi bằng POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? null;
    $productId = $data['productId'] ?? null;
    $quantity = $data['quantity'] ?? 1; // Mặc định số lượng là 1 nếu không có

    // Khởi tạo model giỏ hàng
    $cartModel = new Cart($conn);

    // Xử lý các hành động
    if ($action && $productId) {
        $result = false;
        $message = '';

        switch ($action) {
            case 'add': // Thêm sản phẩm vào giỏ
                $result = $cartModel->addToCart($productId, $quantity);
                $message = $result ? 'Sản phẩm đã được thêm vào giỏ hàng.' : 'Không thể thêm sản phẩm.';
                break;

            case 'remove': // Xóa sản phẩm khỏi giỏ
                $result = $cartModel->removeFromCart($productId);
                $message = $result ? 'Sản phẩm đã được xóa khỏi giỏ hàng.' : 'Không thể xóa sản phẩm.';
                break;

            case 'increase': // Tăng số lượng
                $result = $cartModel->increaseQuantity($productId);
                $message = $result ? 'Tăng số lượng sản phẩm thành công.' : 'Không thể tăng số lượng.';
                break;

            case 'decrease': // Giảm số lượng
                $result = $cartModel->decreaseQuantity($productId);
                $message = $result ? 'Giảm số lượng sản phẩm thành công.' : 'Không thể giảm số lượng.';
                break;

            default:
                echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ.']);
                exit;
        }

        // Nếu hành động thành công, trả về giỏ hàng cập nhật
        if ($result) {
            $cartItems = $cartModel->getCartItems();
            echo json_encode([
                'success' => true,
                'message' => $message,
                'cart' => $cartItems,
                'total' => $cartModel->calculateTotal(), // Tổng giá trị giỏ hàng
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => $message]);
        }
    } else {
        // Nếu dữ liệu không hợp lệ
        echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ.']);
    }
}
