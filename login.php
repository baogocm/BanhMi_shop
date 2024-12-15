<?php
require 'db/connect.php';
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

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
}

body {
    background: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.container {
    width: 100%;
    max-width: 400px;
    padding: 20px;
}

.login-box {
    background: white;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
}

.login-box h2 {
    text-align: center;
    color: #333;
    margin-bottom: 30px;
}

.form-group {
    position: relative;
    margin-bottom: 20px;
}

.form-group i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
}

.form-group input {
    width: 100%;
    padding: 12px 40px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    transition: border-color 0.3s;
}

.form-group input:focus {
    border-color: #ff9a9e;
    outline: none;
}

button {
    width: 100%;
    padding: 12px;
    background: #ff9a9e;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
}

button:hover {
    background: #ff8087;
}

.error-message {
    background: #ffe6e6;
    color: #ff0000;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 20px;
    text-align: center;
}

.links {
    margin-top: 20px;
    text-align: center;
}

.links a {
    color: #666;
    text-decoration: none;
    margin: 0 10px;
    font-size: 14px;
}

.links a:hover {
    color: #ff9a9e;
}

</style>