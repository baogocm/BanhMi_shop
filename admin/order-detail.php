<?php
session_start();
require_once '../models/order.php';
require_once '../db/connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../login.php");
    exit();
}


// Kết nối model
$orderModel = new Order($conn);

// Lấy ID đơn hàng
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: orders.php");
    exit();
}
$order_id = $_GET['id'];

// Lấy thông tin đơn hàng
$order = $orderModel->getOrderById($order_id);
if (!$order) {
    echo "<p>Không tìm thấy đơn hàng!</p>";
    exit();
}

// Lấy danh sách sản phẩm trong đơn hàng
$order_items = $orderModel->getOrderItems($order_id);

// Xử lý cập nhật trạng thái
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_status = $_POST['status'];
    if (in_array($new_status, ['pending', 'completed', 'cancelled'])) {
        $orderModel->updateOrderStatus($order_id, $new_status);
        header("Location: orders.php?id=$order_id");
        exit();
    } else {
        echo "<p>Trạng thái không hợp lệ!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Đơn Hàng</title>
    <link rel="stylesheet" href="../css/admin/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <?php include '../admin/includes/sidebar.php'; ?>

        <div class="main-content">
            <header class="top-bar">
                <h1>Chi Tiết Đơn Hàng #<?php echo $order['id']; ?></h1>
            </header>

            <div class="content-box">
                <h2>Thông Tin Đơn Hàng</h2>
                <p><strong>Khách hàng:</strong> <?php echo $order['username']; ?></p>
                <p><strong>Tổng tiền:</strong> <?php echo number_format($order['total_price']); ?>đ</p>
                <p><strong>Trạng thái:</strong> <?php echo $order['status']; ?></p>
                <p><strong>Ngày đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>

                <form method="POST">
                    <label for="status">Cập nhật trạng thái:</label>
                    <select name="status" id="status">
                        <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="completed" <?php echo $order['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                        <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                    <button type="submit">Cập nhật</button>
                </form>

                <h2>Sản Phẩm</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order_items as $item): ?>
                        <tr>
                            <td><?php echo $item['name']; ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td><?php echo number_format($item['price']); ?>đ</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
