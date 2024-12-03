<?php
require 'db/connect.php';

session_start();


// Bật hiển thị lỗi để debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Truy vấn lấy thông tin người dùng từ bảng users
    $sql = "SELECT * FROM users WHERE username = ?";
    $stm = $conn->prepare($sql);
    $stm->execute([$username]);
    $user = $stm->fetch(PDO::FETCH_ASSOC);

    // Kiểm tra thông tin đăng nhập
	if ($user && $user['password'] === md5($password)) { // Sử dụng MD5 để kiểm tra mật khẩu
		// Lưu thông tin vào session
		$_SESSION['user_id'] = $user['id'];
		$_SESSION['username'] = $user['username'];
	
		// Chuyển hướng đến index.php với thông báo thành công
		header("Location: index.php?login_success=1");
		exit();
	} else {
		// Hiển thị thông báo thất bại
		echo "<script>toastr.error('Sai tên đăng nhập hoặc mật khẩu.');</script>";
	}
	
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - Bánh Mì Shop</title>
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.2.0/css/all.css'>
    <link rel="stylesheet" href="css/styleadmin.css">
</head>
<body>
<div class="container">
	<div class="screen">
		<div class="screen__content">
			<form class="login" method="POST">
				<div class="login__field">
					<i class="login__icon fas fa-user"></i>
					<input type="text" name="username" id="username" class="form-control" required placeholder="User name / Email">
				</div>
				<div class="login__field">
					<i class="login__icon fas fa-lock"></i>
					<input type="password" name="password" id="password" class="form-control" required placeholder="Password">
				</div>
				<button class="button login__submit" type="submit">
					<span class="button__text">Log In Now</span>
					<i class="button__icon fas fa-chevron-right"></i>
				</button>				
			</form>
		</div>
		<div class="screen__background">
			<span class="screen__background__shape screen__background__shape4"></span>
			<span class="screen__background__shape screen__background__shape3"></span>		
			<span class="screen__background__shape screen__background__shape2"></span>
			<span class="screen__background__shape screen__background__shape1"></span>
		</div>		
	</div>
</div>

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
