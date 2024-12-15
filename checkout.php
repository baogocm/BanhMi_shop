<?php
session_start();

// Kiểm tra nếu người dùng chưa đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=checkout.php');
    exit();
}

require_once 'includes/header.php';
require_once 'db/connect.php';

// Lấy thông tin người dùng từ bảng `users`
$userId = $_SESSION['user_id'];
$sql = "SELECT username, email FROM users WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Không tìm thấy thông tin người dùng.";
    exit();
}

// Lấy thông tin giỏ hàng từ bảng `cart`
require_once 'models/cart.php';
$cart = new Cart($conn);
$items = $cart->getCartItems($userId);
$totalAmount = $cart->calculateTotal();

// Nếu giỏ hàng rỗng, chuyển hướng về trang giỏ hàng
if (empty($items)) {
    header('Location: giohang.php');
    exit();
}

// Xử lý khi nhấn nút đặt hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {
    try {
        $conn->beginTransaction();

        // Tạo đơn hàng trong bảng `orders`
        $orderSql = "INSERT INTO orders (user_id, total_price, status) VALUES (:user_id, :total_price, 'pending')";
        $orderStmt = $conn->prepare($orderSql);
        $orderStmt->execute(['user_id' => $userId, 'total_price' => $totalAmount]);
        $orderId = $conn->lastInsertId();

        // Thêm sản phẩm vào bảng `order_items`
        $orderItemSql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)";
        $orderItemStmt = $conn->prepare($orderItemSql);
        foreach ($items as $item) {
            $orderItemStmt->execute([
                'order_id' => $orderId,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        // Xóa giỏ hàng
        $cart->clearCart($userId);

        $conn->commit();

        // Chuyển hướng đến trang xác nhận
        header('Location: order_success.php');
        exit();
    } catch (Exception $e) {
        $conn->rollBack();
        echo "Đã xảy ra lỗi: " . $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận đơn hàng</title>
    <link rel="stylesheet" href="css/pages/checkout.css">
</head>
<body>
    <div class="checkout-container">
        <div class="customer-info">
            <h3>Thông tin khách hàng</h3>
            <p><strong>Tên:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        </div>
        
        <div class="order-details">
            <h3>Chi tiết đơn hàng</h3>
            <table class="order-table">
                <tr>
                    <th>Sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Tổng</th>
                </tr>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo number_format($item['price'], 0, ',', '.'); ?> VND</td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> VND</td>
                </tr>
                <?php endforeach; ?>
            </table>
            <div class="total-amount">
                Tổng thanh toán: <?php echo number_format($totalAmount, 0, ',', '.'); ?> VND
            </div>
        </div>

        <form method="POST" action="">
            <button type="submit" name="confirm_order" class="btn-confirm-order">Xác nhận đặt hàng</button>
        </form>
    </div>
</body>
</html>
