<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Hàng Thành Công</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/pages/order_success.css">
</head>

<body>
    <div class="success-container">
        <i class="fas fa-check-circle success-icon"></i>
        <h1 class="success-message">Cảm ơn bạn đã đặt hàng!</h1>
        
        <div class="order-details">
            <h2>Đơn hàng của bạn đã được xác nhận</h2>
            <p>Chúng tôi sẽ sớm liên hệ với bạn để xác nhận đơn hàng.</p>
        </div>

        <a href="index.php" class="btn-continue-shopping">
            <i class="fas fa-shopping-cart"></i> Tiếp tục mua sắm
        </a>
    </div>

    <script>
        // Chuyển hướng sau 5 giây
        setTimeout(function() {
            window.location.href = "index.php";
        }, 5000);
    </script>
</body>
</html>
