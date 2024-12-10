<?php
session_start();

// Kiểm tra nếu người dùng chưa đăng nhập
if (!isset($_SESSION['user_id'])) {
    // Chuyển hướng đến trang đăng nhập và lưu URL cần quay lại
    header('Location: login.php?redirect=giohang.php');
    exit();
}

// Người dùng đã đăng nhập, xử lý logic giỏ hàng
require_once 'db/connect.php';
require_once 'models/cart.php';

// Khởi tạo model Cart
$cart = new Cart($conn);
$items = $cart->getCartItemCount($_SESSION['user_id']);
$totalAmount = $cart->calculateTotal();

// Hiển thị giao diện giỏ hàng
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ Hàng</title>
    <link rel="stylesheet" href="styles.css">
    <script src="path_to_your_js_file/giohang.js"></script>
</head>
<body>
    <h1>Giỏ Hàng của bạn</h1>
    <table>
        <tr>
            <th>Sản phẩm</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Tổng</th>
            <th>Hành động</th>
        </tr>
        <?php foreach ($items as $item): ?>
        <tr>
            <td><?php echo htmlspecialchars($item['name']); ?></td>
            <td><?php echo number_format($item['price'], 0, ',', '.'); ?> VND</td>
            <td>
                <button class="btn-decrease-quantity" data-product-id="<?php echo $item['product_id']; ?>">-</button>
                <?php echo $item['quantity']; ?>
                <button class="btn-increase-quantity" data-product-id="<?php echo $item['product_id']; ?>">+</button>
            </td>
            <td><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> VND</td>
            <td>
                <button class="btn-remove-from-cart" data-product-id="<?php echo $item['product_id']; ?>">Xóa</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <h3>Tổng thanh toán: <?php echo number_format($totalAmount, 0, ',', '.'); ?> VND</h3>
</body>
</html>
