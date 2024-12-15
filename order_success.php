<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e0f7fa; /* Light blue background */
            font-family: Arial, sans-serif;
        }
        .thank-you-card {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background: #ffffff;
            border: 2px solid #0d47a1;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .thank-you-card h1 {
            font-size: 36px;
            font-weight: bold;
            color: #0d47a1;
        }
        .thank-you-card h2 {
            font-size: 18px;
            margin-top: 10px;
            color: #616161;
        }
        .thank-you-card p {
            margin-top: 20px;
            font-size: 16px;
            color: #424242;
        }
        .thank-you-card .social-handle {
            color: #0d47a1;
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="thank-you-card">
    <h1>Xin Cám Ơn</h1>
    <h2>Vì Đã Đặt HàngHàng</h2>
    <hr>
    <p>Chúng Tôi Hi VọngVọng</p>
    <p>Có Thể Gặp Lại Bạn Trong Tương LaiLai<br>
        <span class="social-handle">@banhmi_shop</span>
    </p>
</div>
<script>
    // Chuyển hướng sau 5 giây
    setTimeout(function() {
        window.location.href = "index.php";
    }, 5000); // 5000ms = 5s
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
