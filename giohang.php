<?php
session_start();
require_once 'db/connect.php';
require_once 'models/cart.php';

$cart = new Cart($conn);

// Xử lý AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    try {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        if (!$data) {
            throw new Exception('Invalid JSON data');
        }

        $action = $data['action'] ?? '';
        $productId = $data['productId'] ?? 0;
        $success = false;

        switch ($action) {
            case 'add':
                $quantity = $data['quantity'] ?? 1;
                $success = $cart->addItem($productId, $quantity);
                break;
            case 'update':
                $change = $data['change'] ?? 0;
                $success = $cart->updateQuantity($productId, $change);
                break;
            case 'remove':
                $success = $cart->removeItem($productId);
                break;
        }

        echo json_encode([
            'success' => $success,
            'total' => $cart->calculateTotal(),
            'cartCount' => $cart->getCartItemCount(),
            'message' => $success ? 'Thao tác thành công' : 'Không thể thực hiện thao tác'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Lỗi: ' . $e->getMessage()
        ]);
    }
    exit;
}

// Hiển thị trang giỏ hàng
$items = $cart->getCartItems();
$totalAmount = $cart->calculateTotal();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng</title>
    <link rel="stylesheet" href="css/pages/giohang.css">
</head>
<body>
    <div class="container">
        <h1>Giỏ Hàng của bạn</h1>
        <?php if (empty($items)): ?>
            <div class="empty-cart">
                <p>Giỏ hàng của bạn đang trống</p>
                <div style="text-align: center;">
                    <button onclick="window.location.href='products.php'" class="btn-return" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold;">Trở về mua sắm</button>
                </div>
            </div>
        <?php else: ?>
            <table class="cart-table">
                <tr>
                    <th>Sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Tổng</th>
                    <th>Hành động</th>
                </tr>
                <?php foreach ($items as $item): ?>
                    <tr data-product-id="<?php echo $item['product_id']; ?>">
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td class="product-price"><?php echo number_format($item['price'], 0, ',', '.'); ?> đ</td>
                        <td>
                            <button class="btn-decrease-quantity">-</button>
                            <span class="quantity"><?php echo $item['quantity']; ?></span>
                            <button class="btn-increase-quantity">+</button>
                        </td>
                        <td class="product-total"><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> đ</td>
                        <td>
                            <button class="btn-remove-from-cart">Xóa</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <div class="total-amount">
                Tổng thanh toán: <span id="cart-total"><?php echo number_format($totalAmount, 0, ',', '.'); ?></span> VND
            </div>
            <form method="POST" action="checkout.php">
                <button type="submit" class="btn-checkout">Thanh toán</button>
            </form>
            <button onclick="window.location.href='index.php'" class="btn-return">Trở về trang chính</button>
        <?php endif; ?>
    </div>
    <script src="js/giohang.js"></script>
</body>
</html>

