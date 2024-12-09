<?php
<<<<<<< HEAD
require 'db/connect.php';
=======
>>>>>>> 8677703faa6a9bfb0ecef8d5b05cb56b4fd31415
session_start();
require_once 'db/connect.php';
require_once 'models/user.php';

<<<<<<< HEAD
// Bật hiển thị lỗi (cho môi trường phát triển)
error_reporting(E_ALL);
ini_set('display_errors', 1);

=======
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
>>>>>>> 8677703faa6a9bfb0ecef8d5b05cb56b4fd31415
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

<<<<<<< HEAD
    // Truy vấn lấy thông tin người dùng
    $sql = "SELECT * FROM users WHERE username = ?";
    $stm = $conn->prepare($sql);
    $stm->execute([$username]);
    $user = $stm->fetch(PDO::FETCH_ASSOC);

    if ($user && md5($password) === $user['password']) {
        // Lưu thông tin vào session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // Thêm role vào session

        // Chuyển hướng theo vai trò
        if ($_SESSION['role'] == 1) {
            header("Location: dashboard/dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        echo "<script>toastr.error('Sai tên đăng nhập hoặc mật khẩu.');</script>";
=======
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
>>>>>>> 8677703faa6a9bfb0ecef8d5b05cb56b4fd31415
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
