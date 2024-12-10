<?php
session_start();

// Kiểm tra nếu người dùng chưa đăng nhập
if (!isset($_SESSION['user_id'])) {
    // Chuyển hướng đến trang đăng nhập và lưu URL cần quay lại
    header('Location: login.php?redirect=checkout.php');
    exit();
}

// Người dùng đã đăng nhập, xử lý logic thanh toán
require_once 'db/connect.php';
require_once 'models/cart.php';

// Khởi tạo model Cart
$cart = new Cart($conn);
$items = $cart->getCartItemCount($_SESSION['user_id']);
$totalAmount = $cart->calculateTotal();

// Hiển thị giao diện thanh toán
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh Toán</title>
</head>
<body>
    <h1>Thông tin Thanh Toán</h1>
    <table>
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
    <h3>Tổng thanh toán: <?php echo number_format($totalAmount, 0, ',', '.'); ?> VND</h3>
</body>
</html>
