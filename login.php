<?php
session_start();
require_once 'db/connect.php';
require_once 'models/user.php';

// Nếu đã đăng nhập thì chuyển về trang chủ
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 1) {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: index.php");
    }
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Debug
    error_log("Login attempt - Username: " . $username);

    // Khởi tạo đối tượng User
    $userModel = new User($conn);
    
    // Thử đăng nhập
    if ($userModel->login($username, $password)) {
        // Chuyển hướng được xử lý trong hàm login
    } else {
        $error = "Sai tên đăng nhập hoặc mật khẩu!";
        error_log("Login failed for username: " . $username);
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - Bánh Mì Shop</title>
    <link rel="stylesheet" href="css/styleadmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <h2>Đăng Nhập</h2>
            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" class="login-form">
                <div class="form-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" required placeholder="Tên đăng nhập" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" required placeholder="Mật khẩu">
                </div>
                
                <button type="submit">Đăng Nhập</button>
            </form>
            
            <div class="links">
                <a href="forgot-password.php">Quên mật khẩu?</a>
                <a href="register.php">Đăng ký</a>
            </div>
        </div>
    </div>
</body>
</html>
