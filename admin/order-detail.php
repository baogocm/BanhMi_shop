<?php
session_start();
require_once '../models/order.php';
require_once '../db/connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../login.php");
    exit();
}



$orderModel = new Order($conn);


if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: orders.php");
    exit();
}
$order_id = $_GET['id'];


$order = $orderModel->getOrderById($order_id);
if (!$order) {
    echo "<p>Không tìm thấy đơn hàng!</p>";
    exit();
}


$order_items = $orderModel->getOrderItems($order_id);


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
    <style>
        .content-box {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin: 20px;
        }

        .content-box h2 {
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }

        .content-box p {
            margin: 10px 0;
            line-height: 1.6;
            color: #666;
        }

        .content-box strong {
            color: #333;
            font-weight: 600;
        }

        form {
            margin: 25px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #555;
            font-weight: 500;
        }

        select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
            font-size: 14px;
            width: 200px;
        }

        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
        }

        button:hover {
            background: #0056b3;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .data-table th,
        .data-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .data-table th {
            background: #f8f9fa;
            color: #333;
            font-weight: 600;
        }

        .data-table tr:hover {
            background: #f8f9fa;
        }

        .data-table td {
            color: #666;
        }

        /* Status colors */
        .status-pending {
            color: #ffc107;
        }

        .status-completed {
            color: #28a745;
        }

        .status-cancelled {
            color: #dc3545;
        }

        @media (max-width: 768px) {
            .content-box {
                margin: 10px;
                padding: 15px;
            }

            select {
                width: 100%;
                margin-bottom: 10px;
            }

            button {
                width: 100%;
            }

            .data-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
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
                <p><strong>Trạng thái:</strong> <span class="status-<?php echo $order['status']; ?>"><?php echo $order['status']; ?></span></p>
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
