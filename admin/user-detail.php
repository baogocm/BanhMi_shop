<?php
session_start();
require_once '../db/connect.php';
require_once '../models/user.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../login.php");
    exit();
}

// Tạo đối tượng User
$userModel = new User($conn);

// Lấy ID người dùng từ URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: users.php");
    exit();
}
$user_id = $_GET['id'];

// Lấy thông tin người dùng
$user = $userModel->getUserById($user_id);

if (!$user) {
    echo "<p>Không tìm thấy người dùng!</p>";
    exit();
}

// Xử lý cập nhật thông tin người dùng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_user'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        if (!empty($username) && !empty($email) && in_array($role, [0, 1])) {
            $userModel->updateUser($user_id, $username, $email, $role);
            header("Location: users.php?id=$user_id");
            exit();
        } else {
            echo "<p>Vui lòng nhập đầy đủ thông tin và chọn vai trò hợp lệ!</p>";
        }
    }

    // Xử lý xóa người dùng
    if (isset($_POST['delete_user'])) {
        $userModel->deleteUser($user_id);
        header("Location: users.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Người Dùng</title>
    <link rel="stylesheet" href="../css/admin/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <?php include '../admin/includes/sidebar.php'; ?>

        <div class="main-content">
            <header class="top-bar">
                <h1>Chi Tiết Người Dùng #<?php echo $user['id']; ?></h1>
            </header>

            <div class="content-box">
                <h2>Thông Tin Người Dùng</h2>
                <form method="POST">
                    <div class="form-group">
                        <label for="username">Tên đăng nhập:</label>
                        <input type="text" name="username" id="username" value="<?php echo $user['username']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" value="<?php echo $user['email']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Vai trò:</label>
                        <select name="role" id="role" required>
                            <option value="0" <?php echo $user['role'] == 0 ? 'selected' : ''; ?>>User</option>
                            <option value="1" <?php echo $user['role'] == 1 ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="submit" name="update_user" class="btn-save">Lưu</button>
                    </div>
                </form>

                <form method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa người dùng này?')">
                    <button type="submit" name="delete_user" class="btn-delete">Xóa Người Dùng</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
