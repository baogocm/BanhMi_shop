<?php
require_once 'models/user.php';
require_once 'db/connect.php';

session_start();


$userModel = new User($conn);


if ($userModel->logout()) {
    
    session_start();
    $_SESSION['message'] = "Đăng xuất thành công!";
}


header("Location: index.php");
exit();
?>
