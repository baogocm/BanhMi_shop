<?php
session_start();
require_once 'db/connect.php';
require_once 'models/user.php';


// Tạo đối tượng User
$userModel = new User($conn);

// Xử lý đăng ký
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $email = trim($_POST['email']);

    // Kiểm tra đầu vào
    if (empty($username) || empty($password) || empty($confirm_password) || empty($email)) {
        $errors[] = "Vui lòng nhập đầy đủ thông tin.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email không hợp lệ.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Mật khẩu xác nhận không khớp.";
    } else {
        // Thực hiện đăng ký
        $isRegistered = $userModel->register($username, $password, $email);
        if ($isRegistered) {
            header("Location: login.php?success=1");
            exit();
        } else {
            $errors[] = "Tên đăng nhập đã tồn tại. Vui lòng chọn tên khác.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <link rel="stylesheet" href="css/styleadmin.css">
</head>
<body>
    <div class="register-container">
        <h1>Đăng Ký</h1>
        <?php if (!empty($errors)): ?>
            <div class="error-messages">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="username">Tên đăng nhập:</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Xác nhận mật khẩu:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>
            <button type="submit">Đăng Ký</button>
        </form>
        <p>Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
    </div>
</body>
</html>
